<?php

namespace Orders\Controller;

use Orders\Service\OrderService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class OrderController extends AbstractActionController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // GET /api/orders — Listar activas
    // GET /api/orders/:id — Obtener una
    // POST /api/orders — Crear
    // PUT /api/orders/:id — Actualizar
    // DELETE /api/orders/:id — Eliminar
    public function indexAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isOptions())
            return new JsonModel([]);

        $id = $this->params()->fromRoute('id');

        if ($request->isGet()) {
            if ($id) {
                $order = $this->orderService->getOrderById((int) $id);
                if (!$order) {
                    $this->getResponse()->setStatusCode(404);
                    return new JsonModel(['success' => false, 'error' => 'Pedido no encontrado']);
                }
                return new JsonModel(['success' => true, 'data' => $order]);
            }
            return new JsonModel([
                'success' => true,
                'data' => $this->orderService->getActiveOrders(),
            ]);
        }

        if ($request->isPost()) {
            $data = json_decode($request->getContent(), true) ?? [];
            if (empty($data['items'])) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(['success' => false, 'error' => 'Items requeridos']);
            }
            $order = $this->orderService->createOrder($data);
            $this->getResponse()->setStatusCode(201);
            return new JsonModel(['success' => true, 'data' => $order]);
        }

        if ($request->isPut() && $id) {
            $data = json_decode($request->getContent(), true) ?? [];
            $order = $this->orderService->updateOrder((int) $id, $data);
            if (!$order) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(['success' => false, 'error' => 'Pedido no encontrado']);
            }
            return new JsonModel(['success' => true, 'data' => $order]);
        }

        if ($request->isDelete() && $id) {
            $ok = $this->orderService->deleteOrder((int) $id);
            if (!$ok) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(['success' => false, 'error' => 'Pedido no encontrado']);
            }
            return new JsonModel(['success' => true, 'message' => 'Pedido eliminado']);
        }

        $this->getResponse()->setStatusCode(405);
        return new JsonModel(['success' => false, 'error' => 'Method not allowed']);
    }

    // POST /api/orders/reorder
    public function reorderAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isOptions())
            return new JsonModel([]);

        if (!$request->isPost()) {
            $this->getResponse()->setStatusCode(405);
            return new JsonModel(['success' => false, 'error' => 'Use POST']);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $orderIds = $data['orderIds'] ?? [];

        if (empty($orderIds)) {
            return new JsonModel(['success' => false, 'error' => 'No IDs provided']);
        }

        $this->orderService->updateOrderSort($orderIds);

        return new JsonModel(['success' => true, 'message' => 'Orders sorted']);
    }

    // PATCH /api/orders/:id/status
    public function statusAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isOptions())
            return new JsonModel([]);

        if (!$request->isPatch() && !$request->isPut()) {
            $this->getResponse()->setStatusCode(405);
            return new JsonModel(['success' => false, 'error' => 'Use PATCH']);
        }

        $id = $this->params()->fromRoute('id');
        $body = json_decode($request->getContent(), true) ?? [];
        $status = $body['status'] ?? null;

        $valid = ['NUEVO', 'PREP', 'ARMADO', 'HORNO', 'LISTO', 'RETIRADO', 'EN_CAMINO', 'ENTREGADO', 'ELIMINADO'];
        if (!$status || !in_array($status, $valid)) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(['success' => false, 'error' => 'Estado inválido']);
        }

        $order = $this->orderService->updateStatus((int) $id, $status);
        if (!$order) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel(['success' => false, 'error' => 'Pedido no encontrado']);
        }

        return new JsonModel(['success' => true, 'data' => $order]);
    }

    // GET /api/orders/kitchen
    public function kitchenAction(): JsonModel
    {
        if ($this->getRequest()->isOptions())
            return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->orderService->getKitchenOrders(),
        ]);
    }

    // GET /api/orders/delivery
    public function deliveryListAction(): JsonModel
    {
        if ($this->getRequest()->isOptions())
            return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->orderService->getDeliveryOrders(),
        ]);
    }

    // GET /api/orders/scheduled
    public function scheduledAction(): JsonModel
    {
        if ($this->getRequest()->isOptions())
            return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->orderService->getScheduledOrders(),
        ]);
    }

    // GET /api/orders/history?status=&payment_method=&delivery_type=&date=
    public function historyAction(): JsonModel
    {
        if ($this->getRequest()->isOptions())
            return new JsonModel([]);

        $filters = [
            'status' => $this->params()->fromQuery('status'),
            'payment_method' => $this->params()->fromQuery('payment_method'),
            'delivery_type' => $this->params()->fromQuery('delivery_type'),
            'date' => $this->params()->fromQuery('date'),
        ];

        return new JsonModel([
            'success' => true,
            'data' => $this->orderService->getHistoryOrders($filters),
        ]);
    }

    // GET /api/estimation/time
    public function estimationAction(): JsonModel
    {
        if ($this->getRequest()->isOptions())
            return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->orderService->getTimeEstimation(),
        ]);
    }

    // POST /api/delivery/geocode
    public function geocodeAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isOptions())
            return new JsonModel([]);

        if (!$request->isPost()) {
            $this->getResponse()->setStatusCode(405);
            return new JsonModel(['success' => false, 'error' => 'Use POST']);
        }

        $body = json_decode($request->getContent(), true) ?? [];
        $address = $body['address'] ?? '';

        if (empty($address)) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(['success' => false, 'error' => 'Dirección requerida']);
        }

        return new JsonModel([
            'success' => true,
            'data' => $this->orderService->geocodeAddress($address),
        ]);
    }

    // GET /api/config — Listar toda config
    // PUT /api/config/:key — Actualizar una config
    public function configAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isOptions())
            return new JsonModel([]);

        $key = $this->params()->fromRoute('key');

        if ($request->isGet()) {
            if ($key) {
                return new JsonModel([
                    'success' => true,
                    'data' => [
                        'key' => $key,
                        'value' => $this->orderService->getConfig($key),
                    ],
                ]);
            }
            return new JsonModel([
                'success' => true,
                'data' => $this->orderService->getAllConfig(),
            ]);
        }

        if ($request->isPut() && $key) {
            $body = json_decode($request->getContent(), true) ?? [];
            $value = $body['value'] ?? null;
            if ($value === null) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(['success' => false, 'error' => 'Value requerido']);
            }
            $this->orderService->setConfig($key, (string) $value);
            return new JsonModel(['success' => true, 'message' => "Config '$key' actualizada"]);
        }

        $this->getResponse()->setStatusCode(405);
        return new JsonModel(['success' => false, 'error' => 'Method not allowed']);
    }
}
