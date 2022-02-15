<?php

namespace Application;

use Indicateur\Controller\IndexController;
use Indicateur\Controller\IndexControllerFactory;
use Indicateur\Provider\Privilege\IndicateurPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                        'abonnement'
                    ],
                    'privileges' => [
                        IndicateurPrivileges::AFFICHER,
                    ],
                ],
            ],

        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'emc2' => [
                                'label' => 'Mon EMC2',
                                'route' => 'mes-indicateurs',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'order'    => 10001,
                                'dropdown-header' => true,
                            ],
                            'indeicateur' => [
                                'label' => 'Mes indicateurs',
                                'route' => 'mes-indicateurs',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'order'    => 10001,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'mes-indicateurs' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mes-indicateurs',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'abonnement' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/abonnement',
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action'     => 'abonnement',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ]

];