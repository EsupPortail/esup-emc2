<?php

namespace Formation;

use Formation\Controller\ImportationLagafController;
use Formation\Controller\ImportationLagafControllerFactory;
use Formation\Provider\Privilege\LagafPrivileges;
use Formation\Service\Stagiaire\StagiaireService;
use Formation\Service\Stagiaire\StagiaireServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ImportationLagafController::class,
                    'action' => [
                        'index',
                        'action',
                        'session',
                        'seance',
                        'stagiaire',
                        'inscription',
                        'presence',
                        'element',
                        'theme',
                    ],
                    'roles' => [
                       // LagafPrivileges::IMPORT_LAGAF,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
//            'formation' => [
//                'type'  => Literal::class,
//                'options' => [
//                    'route'    => '/formation',
//                    'defaults' => [
//                        'controller' => FormationController::class,
//                        'action'     => 'index',
//                    ],
//                ],
//                'may_terminate' => true,
//                'child_routes' => [
                    'import-lagaf' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/importation-lagaf',
                            'defaults' => [
                                /**  @see ImportationLagafController::indexAction() **/
                                'controller' => ImportationLagafController::class,
                                'action' => 'index',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'action' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/action[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'action',
                                    ]
                                ],
                            ],
                            'theme' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/theme[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'theme',
                                    ]
                                ],
                            ],
                            'session' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/session[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'session',
                                    ]
                                ],
                            ],
                            'seance' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/seance[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'seance',
                                    ]
                                ],
                            ],
                            'stagiaire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/stagiaire[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'stagiaire',
                                    ]
                                ],
                            ],
                            'inscription' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/inscription[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'inscription',
                                    ]
                                ],
                            ],
                            'presence' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/presence[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'presence',
                                    ]
                                ],
                            ],
                            'element' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/element[/:id]',
                                    'defaults' => [
                                        'controller' => ImportationLagafController::class,
                                        'action' => 'element',
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
//            ],
//        ],
    ],

    'service_manager' => [
        'factories' => [
            StagiaireService::class => StagiaireServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ImportationLagafController::class => ImportationLagafControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];