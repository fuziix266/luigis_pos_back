<?php

namespace Auth;

use Laminas\Router\Http\Segment;
use Laminas\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'api-auth-login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/auth/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'api-auth-me' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/auth/me',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'me',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Model\UserTable::class => Model\Factory\UserTableFactory::class,
            Service\JwtService::class => Service\Factory\JwtServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'strategies' => ['ViewJsonStrategy'],
    ],
];
