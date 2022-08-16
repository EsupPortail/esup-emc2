<?php

namespace Formation;

use Formation\Controller\FormationController;
use Formation\Controller\PlanFormationController;
use Formation\Controller\PlanFormationControllerFactory;
use Formation\Provider\Privilege\PlanformationPrivileges;
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
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'planformation_' => [
                                'label'    => 'Plan de formation',
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
        'factories' => [],
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