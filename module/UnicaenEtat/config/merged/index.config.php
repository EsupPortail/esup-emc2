<?php

namespace UnicaenEtat;

use UnicaenEtat\Controller\IndexController;
use UnicaenEtat\Controller\IndexControllerFactory;
use UnicaenEtat\Provider\Privilege\EtatPrivileges;
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
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'unicaen-etat' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/unicaen-etat',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaen-etat' => [
                                'label' => 'État',
                                'route' => 'unicaen-etat',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'order'    => 10002,
                                'pages' => [
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];