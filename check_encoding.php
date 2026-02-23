<?php
$pdo = new PDO('mysql:host=localhost;dbname=luigis', 'root', '');
// Connect with latin1 to see the raw bytes
$pdo->exec("SET NAMES latin1");

echo "PIZZAS (latin1 read):\n";
$rows = $pdo->query('SELECT id, name FROM pizzas')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}

echo "\nINGREDIENTS (latin1 read):\n";
$rows = $pdo->query('SELECT id, name FROM ingredients')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}

echo "\nPROMOS (latin1 read):\n";
$rows = $pdo->query('SELECT id, name FROM promos')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}

echo "\nDRINKS (latin1 read):\n";
$rows = $pdo->query('SELECT id, name FROM drinks')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}

echo "\nSIDES (latin1 read):\n";
$rows = $pdo->query('SELECT id, name FROM sides')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "  {$r['id']}: {$r['name']}\n";
}
