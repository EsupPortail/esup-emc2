<?php

namespace Formation;

use Formation\Controller\FormationController;
use Formation\Controller\ImportationLagafController;
use Formation\Controller\ImportationLagafControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                    ],
                    'roles' => [
                        "Administrateur technique",
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation' => [
//                'type'  => Literal::class,
//                'options' => [
//                    'route'    => '/formation',
//                    'defaults' => [
//                        'controller' => FormationController::class,
//                        'action'     => 'index',
//                    ],
//                ],
//                'may_terminate' => true,
                'child_routes' => [
                    'import-lagaf' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/importation-lagaf',
                            'defaults' => [
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