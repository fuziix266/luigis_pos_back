<?php

namespace Orders\Service;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Where;

class OrderService
{
    private AdapterInterface $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    // ==========================================
    // CREAR PEDIDO
    // ==========================================

    public function createOrder(array $data): array
    {
        $sql = new Sql($this->db);

        // Generar número de orden
        $orderNumber = $this->generateOrderNumber();

        // Calcular subtotal and detect delivery fee in items
        $subtotal = 0;
        $deliveryFeeFromItems = 0;
        $items = $data['items'] ?? [];
        foreach ($items as $item) {
            $itemPrice = ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
            if (($item['item_type'] ?? '') === 'delivery_fee') {
                $deliveryFeeFromItems = $itemPrice;
            } else {
                $subtotal += $itemPrice;
            }
        }

        // Delivery fee: use value from items if present, otherwise use base fee if it's a delivery
        $deliveryFee = $deliveryFeeFromItems;
        if ($deliveryFee === 0 && ($data['delivery_type'] ?? 'Local') === 'Delivery') {
            $deliveryFee = (int) $this->getConfig('delivery_base_fee', '3000');
        }

        $totalAmount = $subtotal + $deliveryFee;

        // Si se envió un total manual, este manda
        if (isset($data['manual_total']) && is_numeric($data['manual_total'])) {
            $totalAmount = (int) $data['manual_total'];
        }

        // Encontrar max sort_position p/ activos (para ponerlo al final)
        $maxSortSelect = $sql->select('orders')->columns(['max_sort' => new Expression('MAX(sort_position)')]);
        $maxSortSelect->where(function (Where $where) {
            $where->notIn('status', ['ENTREGADO', 'ELIMINADO']);
        });
        $maxSortResult = $sql->prepareStatementForSqlObject($maxSortSelect)->execute()->current();
        $nextSort = isset($maxSortResult['max_sort']) ? (int) $maxSortResult['max_sort'] + 1 : 0;

        // Insertar orden 
        $insert = $sql->insert('orders');
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => $data['user_id'] ?? null,
            'client_name' => $data['client_name'] ?? null,
            'delivery_type' => $data['delivery_type'] ?? null,
            'payment_method' => $data['payment_method'] ?? null,
            'delivery_address' => $data['delivery_address'] ?? null,
            'address_detail' => $data['address_detail'] ?? null,
            'phone' => $data['phone'] ?? null,
            'status' => 'NUEVO',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $totalAmount,
            'activation_time' => $data['activation_time'] ?? null,
            'notes' => $data['notes'] ?? null,
            'sort_position' => $nextSort,
            'time_created' => date('Y-m-d H:i:s'),
        ];

        $insert->values($orderData);
        $sql->prepareStatementForSqlObject($insert)->execute();

        $orderId = $this->db->getDriver()->getLastGeneratedValue();

        // Insertar items
        foreach ($items as $i => $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $unitPrice = (int) ($item['unit_price'] ?? 0);

            $insertItem = $sql->insert('order_items');
            $insertItem->values([
                'order_id' => $orderId,
                'item_type' => $item['item_type'] ?? 'pizza',
                'item_name' => $item['item_name'] ?? '',
                'details' => $item['details'] ?? null,
                'removed_ingredients' => isset($item['removed_ingredients'])
                    ? json_encode($item['removed_ingredients']) : null,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $qty,
                'comments' => $item['comments'] ?? null,
                'sort_order' => $i,
            ]);
            $sql->prepareStatementForSqlObject($insertItem)->execute();

            $itemId = $this->db->getDriver()->getLastGeneratedValue();

            // Insertar extras
            if (!empty($item['extras'])) {
                foreach ($item['extras'] as $extra) {
                    $insertExtra = $sql->insert('order_item_extras');
                    $insertExtra->values([
                        'order_item_id' => $itemId,
                        'ingredient_id' => $extra['ingredient_id'] ?? null,
                        'ingredient_name' => $extra['ingredient_name'] ?? '',
                        'extra_price' => (int) ($extra['extra_price'] ?? 0),
                    ]);
                    $sql->prepareStatementForSqlObject($insertExtra)->execute();
                }
            }
        }

        return $this->getOrderById((int) $orderId);
    }

    // ==========================================
    // OBTENER PEDIDOS
    // ==========================================

    public function getActiveOrders(): array
    {
        $now = date('Y-m-d H:i:s');
        $sql = new Sql($this->db);
        $select = $sql->select('orders')
            ->where->notIn('status', ['ENTREGADO', 'ELIMINADO']);

        $select2 = $sql->select('orders');
        $select2->where(function (Where $where) use ($now) {
            $where->notIn('status', ['ENTREGADO', 'ELIMINADO']);
            $where->nest()
                ->isNull('activation_time')
                ->or
                ->lessThanOrEqualTo('activation_time', $now)
                ->unnest();
        });
        $select2->order('sort_position ASC, time_created ASC');

        $result = $sql->prepareStatementForSqlObject($select2)->execute();
        return $this->hydrateOrders($result);
    }

    public function getOrderById(int $id): ?array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('orders')->where(['id' => $id]);
        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $row = $result->current();

        if (!$row)
            return null;

        return $this->hydrateOrder($row);
    }

    public function getScheduledOrders(): array
    {
        $now = date('Y-m-d H:i:s');
        $sql = new Sql($this->db);
        $select = $sql->select('orders');
        $select->where(function (Where $where) use ($now) {
            $where->notIn('status', ['ENTREGADO', 'ELIMINADO']);
            $where->isNotNull('activation_time');
            $where->greaterThan('activation_time', $now);
        });
        $select->order('activation_time ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        return $this->hydrateOrders($result);
    }

    public function getKitchenOrders(): array
    {
        $now = date('Y-m-d H:i:s');
        $sql = new Sql($this->db);
        $select = $sql->select('orders');
        $select->where(function (Where $where) use ($now) {
            $where->notIn('status', ['LISTO', 'RETIRADO', 'EN_CAMINO', 'ELIMINADO']);
            $where->isNull('time_completed');
            $where->nest()
                ->isNull('activation_time')
                ->or
                ->lessThanOrEqualTo('activation_time', $now)
                ->unnest();
        });
        $select->order('sort_position ASC, time_created ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $orders = $this->hydrateOrders($result);

        // Filtrar bebidas de los items para vista cocina
        foreach ($orders as &$order) {
            $order['items'] = array_values(array_filter($order['items'], function ($item) {
                return $item['item_type'] !== 'drink';
            }));
        }

        // Excluir pedidos que solo tengan bebidas
        return array_values(array_filter($orders, function ($order) {
            return !empty($order['items']);
        }));
    }

    public function getDeliveryOrders(): array
    {
        $now = date('Y-m-d H:i:s');
        $sql = new Sql($this->db);
        $select = $sql->select('orders');
        $select->where(function (Where $where) use ($now) {
            $where->notIn('status', ['ENTREGADO', 'ELIMINADO']);
            $where->equalTo('delivery_type', 'Delivery');
            $where->nest()
                ->isNull('activation_time')
                ->or
                ->lessThanOrEqualTo('activation_time', $now)
                ->unnest();
        });
        $select->order('time_created ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        return $this->hydrateOrders($result);
    }

    public function getHistoryOrders(array $filters = []): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('orders');
        $select->where(function (Where $where) use ($filters) {
            $where->in('status', ['ENTREGADO', 'ELIMINADO']);

            if (!empty($filters['status']) && $filters['status'] !== 'Todos') {
                $where->equalTo('status', $filters['status']);
            }
            if (!empty($filters['payment_method']) && $filters['payment_method'] !== 'Todos') {
                $where->equalTo('payment_method', $filters['payment_method']);
            }
            if (!empty($filters['delivery_type']) && $filters['delivery_type'] !== 'Todos') {
                $where->equalTo('delivery_type', $filters['delivery_type']);
            }
            if (!empty($filters['date'])) {
                $filterDate = $filters['date'];

                if (!empty($filters['end_date'])) {
                    // Valid date range
                    $startTime = $filterDate . ' 00:00:00'; // Start of the first day
                    // End of the last day
                    $endTime = date('Y-m-d', strtotime($filters['end_date'])) . ' 23:59:59';
                } else {
                    // Single day (using the commercial business day logic starting at 01:00:00 instead, or generic)
                    // Let's stick to the 01:00 to 00:59 logic used previously for single days
                    $startTime = $filterDate . ' 01:00:00';
                    $endTime = date('Y-m-d', strtotime($filterDate . ' +1 day')) . ' 00:59:59';
                }

                $where->between('time_created', $startTime, $endTime);
            }
        });
        $select->order('time_delivered DESC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $orders = $this->hydrateOrders($result);

        // Calcular resumen
        $totalSales = 0;
        $totalDelivered = 0;
        $totalDeleted = 0;
        foreach ($orders as $order) {
            if ($order['status'] === 'ENTREGADO') {
                $totalSales += $order['total_amount'];
                $totalDelivered++;
            } else {
                $totalDeleted++;
            }
        }

        return [
            'orders' => $orders,
            'summary' => [
                'total_orders' => count($orders),
                'total_delivered' => $totalDelivered,
                'total_deleted' => $totalDeleted,
                'total_sales' => $totalSales,
            ],
        ];
    }

    // ==========================================
    // ACTUALIZAR ESTADO
    // ==========================================

    public function updateStatus(int $id, string $newStatus): ?array
    {
        $sql = new Sql($this->db);

        // Verificar que existe
        $order = $this->getOrderById($id);
        if (!$order)
            return null;

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'LISTO' && $order['status'] === 'ENTREGADO') {
            // El pedido ya fue entregado (ej. desde mostrador antes de que cocina terminara).
            // No cambiamos el estado a LISTO para que no regrese a Pedidos Activos.
            // Solo marcamos que la cocina ya lo terminó.
            unset($updateData['status']);
            $updateData['time_completed'] = date('Y-m-d H:i:s');
        } else {
            // Actualizar timestamps según estado
            switch ($newStatus) {
                case 'PREP':
                    $updateData['time_prep'] = date('Y-m-d H:i:s');
                    break;
                case 'ARMADO':
                    $updateData['time_armado'] = date('Y-m-d H:i:s');
                    break;
                case 'HORNO':
                    $updateData['time_entered_oven'] = date('Y-m-d H:i:s');
                    break;
                case 'LISTO':
                    $updateData['time_completed'] = date('Y-m-d H:i:s');
                    break;
                case 'RETIRADO':
                case 'EN_CAMINO':
                    $updateData['time_pickup'] = date('Y-m-d H:i:s');
                    break;
                case 'ENTREGADO':
                    $updateData['time_delivered'] = date('Y-m-d H:i:s');
                    break;
                case 'ELIMINADO':
                    $updateData['time_delivered'] = date('Y-m-d H:i:s');
                    $updateData['is_deleted'] = 1;
                    break;
            }
        }

        $update = $sql->update('orders');
        $update->set($updateData);
        $update->where(['id' => $id]);
        $sql->prepareStatementForSqlObject($update)->execute();

        return $this->getOrderById($id);
    }

    // ==========================================
    // ACTUALIZAR PEDIDO
    // ==========================================

    public function updateOrder(int $id, array $data): ?array
    {
        $sql = new Sql($this->db);

        $order = $this->getOrderById($id);
        if (!$order)
            return null;

        $updateData = [];
        $fields = [
            'client_name',
            'delivery_type',
            'payment_method',
            'delivery_address',
            'address_detail',
            'phone',
            'notes',
            'activation_time',
            'is_paid'
        ];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $update = $sql->update('orders');
            $update->set($updateData);
            $update->where(['id' => $id]);
            $sql->prepareStatementForSqlObject($update)->execute();
        }

        // Si se envían items, reemplazar
        if (isset($data['items'])) {
            // Borrar items existentes (cascade borra extras)
            $delete = $sql->delete('order_items');
            $delete->where(['order_id' => $id]);
            $sql->prepareStatementForSqlObject($delete)->execute();

            // Recalcular subtotal and detect delivery fee in items
            $subtotal = 0;
            $deliveryFeeFromItems = 0;
            foreach ($data['items'] as $i => $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                $unitPrice = (int) ($item['unit_price'] ?? 0);
                $itemPrice = $unitPrice * $qty;

                if (($item['item_type'] ?? '') === 'delivery_fee') {
                    $deliveryFeeFromItems = $itemPrice;
                } else {
                    $subtotal += $itemPrice;
                }

                $insertItem = $sql->insert('order_items');
                $insertItem->values([
                    'order_id' => $id,
                    'item_type' => $item['item_type'] ?? 'pizza',
                    'item_name' => $item['item_name'] ?? '',
                    'details' => $item['details'] ?? null,
                    'removed_ingredients' => isset($item['removed_ingredients'])
                        ? json_encode($item['removed_ingredients']) : null,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $qty,
                    'comments' => $item['comments'] ?? null,
                    'sort_order' => $i,
                ]);
                $sql->prepareStatementForSqlObject($insertItem)->execute();

                $itemId = $this->db->getDriver()->getLastGeneratedValue();

                if (!empty($item['extras'])) {
                    foreach ($item['extras'] as $extra) {
                        $insertExtra = $sql->insert('order_item_extras');
                        $insertExtra->values([
                            'order_item_id' => $itemId,
                            'ingredient_id' => $extra['ingredient_id'] ?? null,
                            'ingredient_name' => $extra['ingredient_name'] ?? '',
                            'extra_price' => (int) ($extra['extra_price'] ?? 0),
                        ]);
                        $sql->prepareStatementForSqlObject($insertExtra)->execute();
                    }
                }
            }

            // Actualizar totales
            // If we found a delivery fee in the new items, use that.
            // If not, but the order type is Delivery, keep the original delivery fee.
            $deliveryFee = $deliveryFeeFromItems;
            if ($deliveryFee === 0 && ($data['delivery_type'] ?? $order['delivery_type']) === 'Delivery') {
                $deliveryFee = $order['delivery_fee'] > 0 ? $order['delivery_fee'] : (int) $this->getConfig('delivery_base_fee', '3000');
            }
            $newTotal = $subtotal + $deliveryFee;

            if (isset($data['manual_total']) && is_numeric($data['manual_total'])) {
                $newTotal = (int) $data['manual_total'];
            }

            $update = $sql->update('orders');
            $update->set([
                'subtotal' => $subtotal,
                'total_amount' => $newTotal,
            ]);
            $update->where(['id' => $id]);
            $sql->prepareStatementForSqlObject($update)->execute();
        } elseif (isset($data['manual_total']) && is_numeric($data['manual_total'])) {
            // Caso donde solo se modifique el total manual sin cambiar items
            $update = $sql->update('orders');
            $update->set(['total_amount' => (int) $data['manual_total']]);
            $update->where(['id' => $id]);
            $sql->prepareStatementForSqlObject($update)->execute();
        }

        return $this->getOrderById($id);
    }

    // ==========================================
    // ELIMINAR (MOVER A HISTORIAL)
    // ==========================================

    public function deleteOrder(int $id): bool
    {
        return $this->updateStatus($id, 'ELIMINADO') !== null;
    }

    // ==========================================
    // GEOCODIFICACIÓN
    // ==========================================

    public function geocodeAddress(string $address): array
    {
        $city = $this->getConfig('store_city', 'Arica');
        $country = $this->getConfig('store_country', 'Chile');
        $baseFee = (int) $this->getConfig('delivery_base_fee', '3000');

        $q = urlencode("$address, $city, $country");
        $url = "https://nominatim.openstreetmap.org/search?q={$q}&format=json&limit=1";

        $ctx = stream_context_create([
            'http' => ['header' => "User-Agent: LuigisPosApp/2.0\r\n"]
        ]);

        $response = @file_get_contents($url, false, $ctx);
        if (!$response) {
            return [
                'lat' => null,
                'lng' => null,
                'zone' => 'Zona Base',
                'base_fee' => $baseFee,
                'extra_charge' => 0,
                'total_fee' => $baseFee,
            ];
        }

        $data = json_decode($response, true);
        if (empty($data)) {
            return [
                'lat' => null,
                'lng' => null,
                'zone' => 'Zona Base',
                'base_fee' => $baseFee,
                'extra_charge' => 0,
                'total_fee' => $baseFee,
            ];
        }

        $lat = (float) $data[0]['lat'];
        $lng = (float) ($data[0]['lon'] ?? 0);

        return [
            'lat' => $lat,
            'lng' => $lng,
            'zone' => 'Zona Base',
            'base_fee' => $baseFee,
            'extra_charge' => 0,
            'total_fee' => $baseFee,
        ];
    }

    // ==========================================
    // ESTIMACIÓN DE TIEMPO
    // ==========================================

    public function getTimeEstimation(): array
    {
        $ovenChambers = (int) $this->getConfig('oven_chambers', '1');
        if ($ovenChambers < 1)
            $ovenChambers = 1;
        if ($ovenChambers > 2)
            $ovenChambers = 2;

        // Contar pizzas en cola
        $sql = new Sql($this->db);
        $select = $sql->select('order_items')
            ->join('orders', 'order_items.order_id = orders.id', [])
            ->columns(['total' => new Expression('SUM(order_items.quantity)')]);
        $select->where(function (Where $where) {
            $where->notIn('orders.status', ['LISTO', 'RETIRADO', 'EN_CAMINO', 'ENTREGADO', 'ELIMINADO']);
            $where->in('order_items.item_type', ['pizza', 'promo']);
        });

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $row = $result->current();
        $pizzasInQueue = (int) ($row['total'] ?? 0);

        // Asumir 2 pizzas de nuevo pedido
        $totalPizzas = $pizzasInQueue + 2;
        $effectivePizzas = $totalPizzas / $ovenChambers;

        if ($effectivePizzas <= 2.1)
            $minutes = 15;
        elseif ($effectivePizzas <= 6.1)
            $minutes = 20;
        elseif ($effectivePizzas <= 8.1)
            $minutes = 25;
        elseif ($effectivePizzas <= 10.1)
            $minutes = 30;
        else {
            $extraSteps = ceil(($effectivePizzas - 10) / 2);
            $minutes = 30 + ($extraSteps * 5);
        }

        return [
            'pizzas_in_queue' => $pizzasInQueue,
            'oven_chambers' => $ovenChambers,
            'estimated_minutes' => (int) $minutes,
        ];
    }

    // ==========================================
    // CONFIG
    // ==========================================

    public function getConfig(string $key, string $default = ''): string
    {
        $sql = new Sql($this->db);
        $select = $sql->select('system_config')
            ->where(['config_key' => $key]);
        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $row = $result->current();
        return $row ? $row['config_value'] : $default;
    }

    public function setConfig(string $key, string $value): void
    {
        $sql = new Sql($this->db);
        $update = $sql->update('system_config');
        $update->set(['config_value' => $value]);
        $update->where(['config_key' => $key]);
        $sql->prepareStatementForSqlObject($update)->execute();
    }

    public function getAllConfig(): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('system_config');
        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $configs = [];
        foreach ($result as $row) {
            $configs[$row['config_key']] = [
                'value' => $row['config_value'],
                'description' => $row['description'],
            ];
        }
        return $configs;
    }

    // ==========================================
    // REORDENAR
    // ==========================================

    public function updateOrderSort(array $orderIds): void
    {
        $sql = new Sql($this->db);
        foreach ($orderIds as $pos => $id) {
            $update = $sql->update('orders');
            $update->set(['sort_position' => $pos]);
            $update->where(['id' => (int) $id]);
            $sql->prepareStatementForSqlObject($update)->execute();
        }
    }

    // ==========================================
    // HELPERS
    // ==========================================

    private function generateOrderNumber(): string
    {
        // El día comercial empieza a las 01:00:00 y termina al día siguiente a las 00:59:59
        // Si son las 00:30, restarle 1 hora nos deja en el día anterior.
        $businessDate = date('Y-m-d', strtotime('-1 hour'));
        $startTime = $businessDate . ' 01:00:00';
        $endTime = date('Y-m-d', strtotime($businessDate . ' +1 day')) . ' 00:59:59';

        $sql = new Sql($this->db);
        $select = $sql->select('orders')
            ->columns(['max_num' => new Expression('MAX(CAST(order_number AS UNSIGNED))')])
            ->where(function (Where $where) use ($startTime, $endTime) {
                $where->between('time_created', $startTime, $endTime);
            });

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $row = $result->current();
        $next = (int) ($row['max_num'] ?? 0) + 1;
        return str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function hydrateOrders($result): array
    {
        $orders = [];
        foreach ($result as $row) {
            $orders[] = $this->hydrateOrder($row);
        }
        return $orders;
    }

    private function hydrateOrder(array $row): array
    {
        $orderId = (int) $row['id'];

        return [
            'id' => $orderId,
            'order_number' => $row['order_number'],
            'client_name' => $row['client_name'],
            'delivery_type' => $row['delivery_type'],
            'payment_method' => $row['payment_method'],
            'delivery_address' => $row['delivery_address'],
            'address_detail' => $row['address_detail'],
            'phone' => $row['phone'],
            'status' => $row['status'],
            'subtotal' => (int) $row['subtotal'],
            'delivery_fee' => (int) $row['delivery_fee'],
            'total_amount' => (int) $row['total_amount'],
            'activation_time' => $row['activation_time'],
            'time_created' => $row['time_created'],
            'time_entered_oven' => $row['time_entered_oven'],
            'time_completed' => $row['time_completed'],
            'time_pickup' => $row['time_pickup'],
            'time_delivered' => $row['time_delivered'],
            'is_deleted' => (bool) $row['is_deleted'],
            'is_paid' => (bool) ($row['is_paid'] ?? false),
            'notes' => $row['notes'],
            'items' => $this->getOrderItems($orderId),
        ];
    }

    private function getOrderItems(int $orderId): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('order_items')
            ->where(['order_id' => $orderId])
            ->order('sort_order ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $items = [];

        foreach ($result as $row) {
            $itemId = (int) $row['id'];
            $items[] = [
                'id' => $itemId,
                'item_type' => $row['item_type'],
                'item_name' => $row['item_name'],
                'details' => $row['details'],
                'removed_ingredients' => $row['removed_ingredients']
                    ? json_decode($row['removed_ingredients'], true) : [],
                'quantity' => (int) $row['quantity'],
                'unit_price' => (int) $row['unit_price'],
                'total_price' => (int) $row['total_price'],
                'comments' => $row['comments'],
                'extras' => $this->getItemExtras($itemId),
            ];
        }

        return $items;
    }

    private function getItemExtras(int $itemId): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('order_item_extras')
            ->where(['order_item_id' => $itemId]);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $extras = [];
        foreach ($result as $row) {
            $extras[] = [
                'id' => (int) $row['id'],
                'ingredient_name' => $row['ingredient_name'],
                'extra_price' => (int) $row['extra_price'],
            ];
        }
        return $extras;
    }
}
