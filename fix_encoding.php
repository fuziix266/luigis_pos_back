<?php
/**
 * Directly fix all encoding-corrupted text in the luigis database
 * by replacing with the correct Spanish text.
 */
$pdo = new PDO('mysql:host=localhost;dbname=luigis;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES utf8mb4");

// === PIZZAS ===
$pizzaFixes = [
    1 => 'Clasica',
    2 => 'Clasica Salame',
    3 => 'Clasica Jamon',
    4 => 'Clasica Champinon',
    5 => 'Clasica Pepperoni',
    6 => 'Napolitana',
    7 => "Di'Pollo",
    8 => 'Hawaiana',
    9 => 'Espanola',
    10 => 'Napoles',
    11 => 'Vegetariana',
    12 => 'Barbecue',
    13 => 'Mediterranea',
    14 => "Luigi's",
    15 => 'Arma Tu Pizza',
];

echo "=== FIXING PIZZAS ===\n";
$stmt = $pdo->prepare("UPDATE pizzas SET name = ? WHERE id = ?");
foreach ($pizzaFixes as $id => $name) {
    $stmt->execute([$name, $id]);
    echo "  Pizza $id => $name\n";
}

// === INGREDIENTS ===
$ingredientFixes = [
    1 => 'Aceitunas',
    2 => 'Albahaca',
    3 => 'Camarones',
    4 => 'Carne',
    5 => 'Champinon',
    6 => 'Choclo',
    7 => 'Chorizo Espanol',
    8 => 'Crema',
    9 => 'Jamon',
    10 => 'Jamon Serrano',
    22 => 'Oregano',
    14 => 'Pepperoni',
    11 => 'Pimenton',
    12 => 'Pina',
    13 => 'Pollo',
    15 => 'Queso',
    16 => 'Queso Parmesano',
    17 => 'Salame',
    18 => 'Salsa Barbecue',
    21 => 'Salsa de tomate',
    19 => 'Tocino',
    20 => 'Tomate Cherry',
];

echo "\n=== FIXING INGREDIENTS ===\n";
$stmt = $pdo->prepare("UPDATE ingredients SET name = ? WHERE id = ?");
foreach ($ingredientFixes as $id => $name) {
    $stmt->execute([$name, $id]);
    echo "  Ingredient $id => $name\n";
}

// === PROMOS ===
$promoFixes = [
    1 => ['name' => 'Promo 1', 'description' => '2 Pizzas Clasicas (opcion 3ra pizza por $18.000)'],
    2 => ['name' => 'Promo 2', 'description' => '2 Pizzas + Palitos Ajo + Bebida 1.5L'],
    3 => ['name' => 'Promo del Dia', 'description' => null],
];

echo "\n=== FIXING PROMOS ===\n";
$stmt = $pdo->prepare("UPDATE promos SET name = ?, description = ? WHERE id = ?");
foreach ($promoFixes as $id => $data) {
    $stmt->execute([$data['name'], $data['description'], $id]);
    echo "  Promo $id => {$data['name']}\n";
}

echo "\n=== DONE ===\n";

// Verify
echo "\n=== VERIFICATION ===\n";
echo "PIZZAS:\n";
foreach ($pdo->query('SELECT id, name FROM pizzas ORDER BY id')->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}
echo "\nINGREDIENTS:\n";
foreach ($pdo->query('SELECT id, name FROM ingredients ORDER BY id')->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}
echo "\nPROMOS:\n";
foreach ($pdo->query('SELECT id, name, description FROM promos ORDER BY id')->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "  {$r['id']}: {$r['name']} | {$r['description']}\n";
}
