<?php
$pdo = new PDO('mysql:host=localhost;dbname=luigis;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE order_items;");
    $pdo->exec("TRUNCATE TABLE order_status_history;");
    $pdo->exec("TRUNCATE TABLE orders;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Todas las órdenes y su historial han sido eliminadas.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
