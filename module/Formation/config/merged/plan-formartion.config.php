<?php

namespace Formation;

use Formation\Controller\FormationController;
use Formation\Controller\FormationGroupeController;
use Formation\Controller\FormationInstanceController;
use Formation\Controller\PlanFormationController;
use Formation\Controller\PlanFormationControllerFactory;
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
                    'controller' => PlanFormationController::class,
                    'action' => [
                        'afficher',
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
                                'label' => "Gestion de l'offre de formation",
                                'route' => 'home',
                                'resources' => [
                                    PrivilegeController::getResourceId(PlanFormationController::class, 'afficher') ,
                                    PrivilegeController::getResourceId(FormationGroupeController::class, 'index') ,
                                    PrivilegeController::getResourceId(FormationController::class, 'index') ,
                                    PrivilegeController::getResourceId(FormationInstanceController::class, 'index') ,
                                ],
                                'order'    => 300,
                                'dropdown-header' => true,
                            ],
                            'planformation_' => [
                                'label'    => 'Plan de formation à venir',
                                'route'    => 'plan-formation',
                                'resource' => PrivilegeController::getResourceId(PlanFormationController::class, 'afficher') ,
                                'order'    => 302,
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
            'plan-formation' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/plan-formation[/:annee]',
                    'defaults' => [
                        'controller' => PlanFormationController::class,
                        'action'     => 'afficher',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
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
            PlanFormationController::class => PlanFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];