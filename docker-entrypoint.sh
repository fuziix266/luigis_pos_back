#!/bin/bash
set -e

# Generar config/autoload/local.php desde variables de entorno
cat > /var/www/html/config/autoload/local.php <<PHPEOF
<?php
// Configuración generada automáticamente desde variables de entorno
return [
    'db' => [
        'driver' => 'Pdo',
        'dsn'    => 'mysql:dbname=${DB_DATABASE};host=${DB_HOST};port=${DB_PORT};charset=utf8mb4',
        'username' => '${DB_USERNAME}',
        'password' => '${DB_PASSWORD}',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        ],
    ],
    'jwt' => [
        'secret'     => '${JWT_SECRET:-luigis_pos_jwt_prod_secret_2026_x9k}',
        'expiration' => ${JWT_EXPIRATION:-86400},
    ],
];
PHPEOF

# Ejecutar Apache
exec apache2-foreground
