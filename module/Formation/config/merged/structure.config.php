<?php

namespace Formation;

use Formation\Controller\StructureController;
use Formation\Controller\StructureControllerFactory;
use Formation\Provider\Privilege\FormationstructurePrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'index',
                    ],
                    'privilege' => [
                        FormationstructurePrivileges::getResourceId(FormationstructurePrivileges::FORMATIONSTRUCTURE_INDEX),
                    ],
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'afficher',
                        'lister-les-agents',
                    ],
                    'privilege' => [
                        FormationstructurePrivileges::getResourceId(FormationstructurePrivileges::FORMATIONSTRUCTURE_AFFICHER),
                        FormationstructurePrivileges::getResourceId(FormationstructurePrivileges::FORMATIONSTRUCTURE_MESSTRUCTURES),
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
                        'order' => 200,
                        'label' => 'Mes structures',
                        'route' => 'formation/structure',
                        'resource' => FormationstructurePrivileges::getResourceId(FormationstructurePrivileges::FORMATIONSTRUCTURE_MESSTRUCTURES),
                        'dropdown-header' => true,
                        'pages' => [],
                    ],
                    'gestion-formation' => [
                        'pages' => [

                            [
                                'label' => 'Gestion des ressources',
                                'route' => 'formation/structure/index',
                                'resource' => //todo revoir la navigation
                                    FormationstructurePrivileges::getResourceId(FormationstructurePrivileges::FORMATIONSTRUCTURE_INDEX),
//                                    FormationagentPrivileges::getResourceId(FormationagentPrivileges::FORMATIONAGENT_INDEX)
                                'order'    => 400,
                                'dropdown-header' => true,
                            ],
                            'structure' => [
                                'order' => 420,
                                'label' => 'Structure',
                                'route' => 'formation/structure-index',
                                'resource' =>
                                    FormationstructurePrivileges::getResourceId(FormationstructurePrivileges::FORMATIONSTRUCTURE_INDEX),
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
                            'lister-les-agents' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/lister-les-agents',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'lister-les-agents',
                                    ],
                                ],
                                'may_terminate' => true,
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