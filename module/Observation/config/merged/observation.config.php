<?php

namespace MissionSpecifique;

use Observation\Service\Observation\ObservationService;
use Observation\Service\Observation\ObservationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
//                [
//                    'controller' => MissionSpecifiqueController::class,
//                    'action' => [
//                        'detruire',
//                    ],
//                    'privileges' => [
//                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_DETRUIRE,
//                    ],
//                ],
            ],
        ],
    ],


    'router'          => [
        'routes' => [
//            'observation' => [
//                'type'  => Literal::class,
//                'options' => [
//                    'route'    => '/type',
//                    'defaults' => [
//                        'controller' => MissionSpecifiqueController::class,
//                        'action'     => 'index',
//                    ],
//                ],
//                'may_terminate' => true,
//                'child_routes' => [
//                    'afficher' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/afficher/:mission',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'afficher',
//                            ],
//                        ],
//                    ],
//                    'ajouter' => [
//                        'type'  => Literal::class,
//                        'options' => [
//                            'route'    => '/ajouter',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'ajouter',
//                            ],
//                        ],
//                    ],
//                    'modifier' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/modifier/:mission',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'modifier',
//                            ],
//                        ],
//                    ],
//                    'historiser' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/historiser/:mission',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'historiser',
//                            ],
//                        ],
//                    ],
//                    'restaurer' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/restaurer/:mission',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'restaurer',
//                            ],
//                        ],
//                    ],
//                    'detruire' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/detruire/:mission',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'detruire',
//                            ],
//                        ],
//                    ],
//                ],
//            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ObservationService::class => ObservationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
//            MissionSpecifiqueController::class => MissionSpecifiqueControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
//            MissionSpecifiqueForm::class => MissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
//            MissionSpecifiqueHydrator::class => MissionSpecifiqueHydratorFactory::class,
        ],
    ]

];