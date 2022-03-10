<?php

namespace Carriere;

use Carriere\Controller\CorpsController;
use Carriere\Controller\CorpsControllerFactory;
use Carriere\Provider\Privilege\CorpsPrivileges;
use Carriere\Service\Corps\CorpsService;
use Carriere\Service\Corps\CorpsServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_INDEX,
                    ],
                ],
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'afficher-agents',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_AFFICHER,
                    ],
                ],
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'modifier-niveau',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'order' => 820,
                                'label' => 'Corps',
                                'route' => 'corps',
                                'resource' => PrivilegeController::getResourceId(CorpsController::class, 'index') ,
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
            'corps' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/corps',
                    'defaults' => [
                        'controller' => CorpsController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agents/:corps',
                            'defaults' => [
                                'controller' => CorpsController::class,
                                'action'     => 'afficher-agents',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-niveau' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-niveau/:corps',
                            'defaults' => [
                                'controller' => CorpsController::class,
                                'action'     => 'modifier-niveau',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CorpsService::class => CorpsServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CorpsController::class => CorpsControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];