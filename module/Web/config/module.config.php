<?php

namespace Web;

return [
    'router' => [
        'routes' => [
            'web-home' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'web-pizzas' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/pizzas',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'pizzas',
                    ],
                ],
            ],
            'web-promociones' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/promociones',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'promociones',
                    ],
                ],
            ],
            'web-arma-tu-pizza' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/arma-tu-pizza',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'arma-tu-pizza',
                    ],
                ],
            ],
            'web-ingredientes-extra' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/ingredientes-extra',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'ingredientes-extra',
                    ],
                ],
            ],
            'web-bebidas' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/bebidas-y-otros',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'bebidas',
                    ],
                ],
            ],
            'web-promo-dia' => [
                'type'    => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route'    => '/promo-del-dia',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'promo-dia',
                    ],
                ],
            ],
            'web-promo-dia-especifico' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/promo/:dia',
                    'constraints' => [
                        'dia' => 'lunes|martes|miercoles|jueves|viernes|sabado|domingo',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'promo-dia',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'web' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'web/layout' => __DIR__ . '/../view/layout/web-layout.phtml',
        ],
    ],
];
