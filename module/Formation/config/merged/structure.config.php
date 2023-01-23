<?php

namespace Formation;

use Formation\Controller\StructureController;
use Formation\Controller\StructureControllerFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Provider\Privilege\PlanformationPrivileges;
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
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [ //TODO privileges
                        PlanformationPrivileges::PLANFORMATION_COURANT,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'mes-structures' => [
                        'order' => 100,
                        'label' => 'Mes structures',
                        'route' => 'formation/structure',
//                        'route' => 'gestion',
                        'resource' => PrivilegeController::getResourceId(StructureController::class, 'afficher'),
                        'dropdown-header' => true,
                        'pages' => [],
                    ],
                    'gestion-formation' => [
                        'pages' => [

                            [
                                'label' => 'Gestion des ressources',
                                'route' => 'formation/structure/index',
                                'resource' =>
                                    PrivilegeController::getResourceId(StructureController::class, 'index'),

                                'order'    => 400,
                                'dropdown-header' => true,
                            ],
                            'structure' => [
                                'order' => 420,
                                'label' => 'Structure',
                                'route' => 'formation/structure-index',
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
                    'structure-index' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/structure-index',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'structure' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/structure[/:structure]',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'index' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/index',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'index',
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