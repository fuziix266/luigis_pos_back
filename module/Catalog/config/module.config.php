<?php

namespace Catalog;

use Laminas\Router\Http\Segment;
use Laminas\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'api-catalog-pizzas' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/catalog/pizzas[/:id]',
                    'constraints' => ['id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action' => 'pizzas',
                    ],
                ],
            ],
            'api-catalog-ingredients' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/catalog/ingredients',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action' => 'ingredients',
                    ],
                ],
            ],
            'api-catalog-drinks' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/catalog/drinks',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action' => 'drinks',
                    ],
                ],
            ],
            'api-catalog-sides' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/catalog/sides',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action' => 'sides',
                    ],
                ],
            ],
            'api-catalog-sizes' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/catalog/sizes',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action' => 'sizes',
                    ],
                ],
            ],
            'api-catalog-promos' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/catalog/promos[/:action]',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action' => 'promos',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\CatalogController::class => Controller\Factory\CatalogControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\CatalogService::class => Service\Factory\CatalogServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'strategies' => ['ViewJsonStrategy'],
    ],
];
