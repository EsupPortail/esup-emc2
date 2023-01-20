<?php

namespace Formation;

use Formation\Controller\PlanDeFormationController;
use Formation\Controller\PlanDeFormationControllerFactory;
use Formation\Provider\Privilege\PlanformationPrivileges;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceFactory;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'courant',
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_ACCES
                    ],
                ],
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'ajouter',
                        'modifier',
                        'supprimer'
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_ACCES
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
                                'label' => "Gestion du plan de formation",
                                'route' => 'home',
                                'resources' => [
                                    PrivilegeController::getResourceId(PlanDeFormationController::class, 'courant') ,
                                    PrivilegeController::getResourceId(PlanDeFormationController::class, 'index') ,
                                ],
                                'order'    => 100,
                                'dropdown-header' => true,
                            ],
                            'planformation_courrant' => [
                                'label'    => 'Plan de formation courant',
                                'route'    => 'plan-de-formation/courant',
                                'resource' => PrivilegeController::getResourceId(PlanDeFormationController::class, 'courant') ,
                                'order'    => 110,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'plansformation' => [
                                'label'    => 'Plans de formation',
                                'route'    => 'plan-de-formation',
                                'resource' => PrivilegeController::getResourceId(PlanDeFormationController::class, 'index') ,
                                'order'    => 120,
                                'icon' => 'fas fa-angle-right',
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'plan-de-formation' => [
                'type'  => Segment::class,
                'options' => [
                    /** @see PlanDeFormationController::indexAction() */
                    'route'    => '/plan-de-formation',
                    'defaults' => [
                        'controller' => PlanDeFormationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'courant' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see PlanDeFormationController::courantAction() */
                            'route'    => '/courant',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'courant',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            PlanDeFormationService::class => PlanDeFormationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            PlanDeFormationController::class => PlanDeFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];