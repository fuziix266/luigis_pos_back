<?php
$pdo = new PDO('mysql:host=localhost;dbname=luigis;charset=utf8mb4', 'root', '');
$pdo->exec("SET NAMES utf8mb4");

echo "=== INGREDIENTES DISPONIBLES (EXTRAS) ===\n";
$ingredients = $pdo->query("SELECT * FROM ingredients ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
foreach ($ingredients as $ing) {
    echo "ID: {$ing['id']} | Nombre: {$ing['name']} | Precio Extra: {$ing['price']}\n";
}

echo "\n=== RECETAS DE PIZZAS (INGREDIENTES POR PIZZA) ===\n";
$sql = "SELECT p.name as pizza_name, i.name as ingredient_name 
        FROM pizza_ingredients pi 
        JOIN pizzas p ON pi.pizza_id = p.id 
        JOIN ingredients i ON pi.ingredient_id = i.id 
        ORDER BY p.name, i.name";
$recipes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$currentPizza = '';
foreach ($recipes as $r) {
    if ($r['pizza_name'] !== $currentPizza) {
        echo "\nPizza: {$r['pizza_name']}\n";
        $currentPizza = $r['pizza_name'];
    }
    echo "  - {$r['ingredient_name']}\n";
}
