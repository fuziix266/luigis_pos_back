#!/bin/bash
set -e

# Generar config/autoload/local.php desde variables de entorno
cat > /var/www/html/config/autoload/local.php <<PHPEOF
<?php
// Configuración generada automáticamente desde variables de entorno
return [
    'db' => [
        'driver' => 'Pdo',
        'dsn'    => 'mysql:dbname=${DB_NAME};host=${DB_HOST};port=${DB_PORT};charset=utf8mb4',
        'username' => '${DB_USER}',
        'password' => '${DB_PASS}',
    ],
    'jwt' => [
        'secret'     => '${JWT_SECRET}',
        'expiration' => ${JWT_EXPIRATION:-86400},
    ],
];
PHPEOF

# Ejecutar Apache
exec apache2-foreground
