<?php

namespace Orders;

use Laminas\Router\Http\Segment;
use Laminas\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'api-orders' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/orders[/:id]',
                    'constraints' => ['id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'api-orders-status' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/orders/:id/status',
                    'constraints' => ['id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'status',
                    ],
                ],
            ],
            'api-orders-reorder' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/orders/reorder',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'reorder',
                    ],
                ],
            ],
            'api-orders-kitchen' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/orders/kitchen',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'kitchen',
                    ],
                ],
            ],
            'api-orders-delivery' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/orders/delivery',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'deliveryList',
                    ],
                ],
            ],
            'api-orders-scheduled' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/orders/scheduled',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'scheduled',
                    ],
                ],
            ],
            'api-orders-history' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/orders/history',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'history',
                    ],
                ],
            ],
            'api-orders-estimation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/estimation/time',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'estimation',
                    ],
                ],
            ],
            'api-delivery-geocode' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/delivery/geocode',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'geocode',
                    ],
                ],
            ],
            'api-config' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/config[/:key]',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'config',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\OrderController::class => Controller\Factory\OrderControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\OrderService::class => Service\Factory\OrderServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'strategies' => ['ViewJsonStrategy'],
    ],
];
