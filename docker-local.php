<?php
// Configuración de producción — usada por Docker
return [
    'db' => [
        'driver' => 'Pdo',
        'dsn'    => 'mysql:dbname=luigis;host=62.146.181.70;port=3360;charset=utf8mb4',
        'username' => 'user',
        'password' => 'admin@123',
    ],
    'jwt' => [
        'secret'     => 'luigis_pos_jwt_prod_secret_2026_x9k',
        'expiration' => 86400,
    ],
];
