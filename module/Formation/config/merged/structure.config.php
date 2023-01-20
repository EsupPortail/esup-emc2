<?php

namespace Formation;

use Formation\Controller\FormationController;
use Formation\Controller\StructureController;
use Formation\Controller\StructureControllerFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [ //TODO privileges
                        FormationPrivileges::FORMATION_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            [
                                'label' => 'Gestion des ressources',
                                'route' => 'formation/structure',
                                'resources' => [
                                    PrivilegeController::getResourceId(StructureController::class, 'index'),
                                ],
                                'order'    => 10000,
                                'dropdown-header' => true,
                            ],
                            'structure' => [
                                'order' => 12000,
                                'label' => 'Structure',
                                'route' => 'formation/structure',
                                'resource' => PrivilegeController::getResourceId(StructureController::class, 'index'),
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
            'formation' => [
                'child_routes' => [
                    'structure' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:structure',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'afficher',
                                    ],
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
            StructureController::class => StructureControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];