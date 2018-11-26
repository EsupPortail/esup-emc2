<?php

namespace Application;

use Application\Controller\RessourceRh\RessourceRhController;
use Application\Controller\RessourceRh\RessourceRhControllerFactory;
use Application\Form\RessourceRh\AgentStatusForm;
use Application\Form\RessourceRh\AgentStatusFormFactory;
use Application\Form\RessourceRh\AgentStatusHydrator;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorpsFormFactory;
use Application\Form\RessourceRh\CorpsHydrator;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Form\RessourceRh\CorrespondanceFormFactory;
use Application\Form\RessourceRh\CorrespondanceHydrator;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\RessourceRh\RessourceRhServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'index',
                        'creer-agent-status',
                        'modifier-agent-status',
                        'effacer-agent-status',
                        'creer-correspondance',
                        'modifier-correspondance',
                        'effacer-correspondance',
                        'creer-corps',
                        'modifier-corps',
                        'effacer-corps',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'ressource-rh' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/ressource-rh',
                    'defaults' => [
                        'controller' => RessourceRhController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'agent-status' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/agent-status',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-agent-status',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-agent-status',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-agent-status',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'correspondance' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/correspondance',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-correspondance',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-correspondance',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-correspondance',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'corps' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/corps',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-corps',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-corps',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-corps',
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
        'invokables' => [
        ],
        'factories' => [
            RessourceRhService::class => RessourceRhServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RessourceRhController::class => RessourceRhControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentStatusForm::class => AgentStatusFormFactory::class,
            CorpsForm::class => CorpsFormFactory::class,
            CorrespondanceForm::class => CorrespondanceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokable' => [
            AgentStatusHydrator::class => AgentStatusHydrator::class,
            CorpsHydrator::class => CorpsHydrator::class,
            CorrespondanceHydrator::class => CorrespondanceHydrator::class,
        ]
    ]

];