<?php

use Utilisateur\Controller\PrivilegeController;
use Utilisateur\Controller\PrivilegeControllerFactory;
use Utilisateur\Service\Privilege\PrivilegeService;
use Utilisateur\Service\Privilege\PrivilegeServiceFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [

    'bjyauthorize' => [
        'guards' => [
            UnicaenAuth\Guard\PrivilegeController::class => [
                [
                    'controller' => PrivilegeController::class,
                    'action' => [
                        'index',
                        'modifier',
                    ],
                    'roles' => [
                        "Administrateur technique",
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'invokables' => [],
        'factories' => [
            PrivilegeController::class  => PrivilegeControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            PrivilegeService::class => PrivilegeServiceFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'privilege' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/privilege',
                    'defaults' => [
                        'controller' => PrivilegeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                ],
            ],
            'modifier-privilege' => [
                'type'          => Segment::class,
                'options'       => [
                    'route'    => '/modifier-privilege/:role/:privilege',
                    'defaults' => [
                        'controller'    => PrivilegeController::class,
                        'action'        => 'modifier',
                    ],
                ],
            ],

        ],
    ],

    '\Zend\Navigation\Navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'privilege' => [
                                'label' => 'Privilège',
                                'route' => 'privilege',
                                'resource' => \UnicaenAuth\Guard\PrivilegeController::getResourceId('UnicaenAuth\Controller\Droits', 'privileges'),
                                'order'    => 2010,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];