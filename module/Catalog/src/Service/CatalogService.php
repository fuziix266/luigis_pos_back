<?php

namespace Catalog\Service;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;

class CatalogService
{
    private AdapterInterface $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    // ==========================================
    // PIZZAS
    // ==========================================

    public function getAllPizzas(): array
    {
        $sql = new Sql($this->db);

        // Pizzas con categoría
        $select = $sql->select('pizzas')
            ->join('categories', 'pizzas.category_id = categories.id', [
                'category_name' => 'name',
                'category_display' => 'display_name',
            ])
            ->order('pizzas.sort_order ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $pizzas = [];

        foreach ($result as $row) {
            $pizzaId = (int) $row['id'];
            $pizza = [
                'id' => $pizzaId,
                'name' => $row['name'],
                'category' => [
                    'id' => (int) $row['category_id'],
                    'name' => $row['category_name'],
                    'display_name' => $row['category_display'],
                ],
                'is_available' => (bool) $row['is_available'],
                'is_customizable' => (bool) $row['is_customizable'],
                'prices' => $this->getPizzaPrices($pizzaId),
                'ingredients' => $this->getPizzaIngredients($pizzaId),
            ];
            $pizzas[] = $pizza;
        }

        return $pizzas;
    }

    public function getPizzaById(int $id): ?array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('pizzas')
            ->join('categories', 'pizzas.category_id = categories.id', [
                'category_name' => 'name',
                'category_display' => 'display_name',
            ])
            ->where(['pizzas.id' => $id]);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $row = $result->current();

        if (!$row) return null;

        return [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'category' => [
                'id' => (int) $row['category_id'],
                'name' => $row['category_name'],
                'display_name' => $row['category_display'],
            ],
            'is_available' => (bool) $row['is_available'],
            'is_customizable' => (bool) $row['is_customizable'],
            'prices' => $this->getPizzaPrices((int) $row['id']),
            'ingredients' => $this->getPizzaIngredients((int) $row['id']),
        ];
    }

    private function getPizzaPrices(int $pizzaId): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('pizza_prices')
            ->join('pizza_sizes', 'pizza_prices.size_id = pizza_sizes.id', [
                'size_name' => 'name',
                'size_display' => 'display_name',
                'extra_price',
            ])
            ->where(['pizza_prices.pizza_id' => $pizzaId])
            ->order('pizza_sizes.sort_order ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $prices = [];
        foreach ($result as $row) {
            $prices[$row['size_name']] = [
                'size_id' => (int) $row['size_id'],
                'size_name' => $row['size_display'],
                'price' => (int) $row['price'],
                'extra_price' => (int) $row['extra_price'],
            ];
        }
        return $prices;
    }

    private function getPizzaIngredients(int $pizzaId): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('pizza_ingredients')
            ->join('ingredients', 'pizza_ingredients.ingredient_id = ingredients.id', ['ingredient_name' => 'name'])
            ->where(['pizza_ingredients.pizza_id' => $pizzaId])
            ->order('ingredients.sort_order ASC');

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $ingredients = [];
        foreach ($result as $row) {
            $ingredients[] = [
                'id' => (int) $row['ingredient_id'],
                'name' => $row['ingredient_name'],
                'is_base' => (bool) $row['is_base'],
            ];
        }
        return $ingredients;
    }

    // ==========================================
    // INGREDIENTES
    // ==========================================

    public function getAllIngredients(): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('ingredients')->order('sort_order ASC');
        $result = $sql->prepareStatementForSqlObject($select)->execute();

        $items = [];
        foreach ($result as $row) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'is_available' => (bool) $row['is_available'],
            ];
        }
        return $items;
    }

    // ==========================================
    // BEBIDAS
    // ==========================================

    public function getAllDrinks(): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('drinks')->order('sort_order ASC');
        $result = $sql->prepareStatementForSqlObject($select)->execute();

        $items = [];
        foreach ($result as $row) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'price' => (int) $row['price'],
                'is_available' => (bool) $row['is_available'],
            ];
        }
        return $items;
    }

    // ==========================================
    // ACOMPAÑAMIENTOS
    // ==========================================

    public function getAllSides(): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('side_dishes')->order('sort_order ASC');
        $result = $sql->prepareStatementForSqlObject($select)->execute();

        $items = [];
        foreach ($result as $row) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'price' => (int) $row['price'],
                'is_available' => (bool) $row['is_available'],
            ];
        }
        return $items;
    }

    // ==========================================
    // TAMAÑOS
    // ==========================================

    public function getAllSizes(): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('pizza_sizes')->order('sort_order ASC');
        $result = $sql->prepareStatementForSqlObject($select)->execute();

        $items = [];
        foreach ($result as $row) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'display_name' => $row['display_name'],
                'extra_price' => (int) $row['extra_price'],
            ];
        }
        return $items;
    }

    // ==========================================
    // PROMOS
    // ==========================================

    public function getAllPromos(): array
    {
        $sql = new Sql($this->db);
        $select = $sql->select('promos')
            ->where(['is_active' => 1])
            ->order('sort_order ASC');
        $result = $sql->prepareStatementForSqlObject($select)->execute();

        $items = [];
        foreach ($result as $row) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'code' => $row['code'],
                'description' => $row['description'],
                'base_price' => (int) $row['base_price'],
            ];
        }
        return $items;
    }

    public function getPromoToday(): ?array
    {
        $dayOfWeek = (int) date('w'); // 0=Sunday ... 6=Saturday

        $sql = new Sql($this->db);
        $select = $sql->select('promo_day_config')
            ->where(['day_of_week' => $dayOfWeek]);

        $result = $sql->prepareStatementForSqlObject($select)->execute();
        $config = $result->current();

        if (!$config) return null;

        $days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        $data = [
            'day_of_week' => $dayOfWeek,
            'day_name' => $days[$dayOfWeek],
            'is_closed' => (bool) $config['is_closed'],
            'pizza' => null,
            'promo_price' => 17000,
        ];

        if (!$config['is_closed'] && $config['pizza_id']) {
            $data['pizza'] = $this->getPizzaById((int) $config['pizza_id']);
        }

        return $data;
    }
}
