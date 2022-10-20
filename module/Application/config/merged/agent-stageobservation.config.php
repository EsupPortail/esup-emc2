<?php

namespace Application;

use Application\Controller\AgentStageObservationController;
use Application\Controller\AgentStageObservationControllerFactory;
use Application\Form\AgentStageObservation\AgentStageObservationForm;
use Application\Form\AgentStageObservation\AgentStageObservationFormFactory;
use Application\Form\AgentStageObservation\AgentStageObservationHydrator;
use Application\Form\AgentStageObservation\AgentStageObservationHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\AgentStageObservation\AgentStageObservationService;
use Application\Service\AgentStageObservation\AgentStageObservationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentStageObservationController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_GESTION_CCC,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'agent' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/agent',
                ],
                'child_routes' => [
                    'stageobs' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/stage-observation',
                            'defaults' => [
                                'controller' => AgentStageObservationController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentStageObservationController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentStageObservationController::class,
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentStageObservationController::class,
                                        'action' => 'historiser'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentStageObservationController::class,
                                        'action' => 'restaurer'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentStageObservationController::class,
                                        'action' => 'detruire'
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
        'factories' => [
            AgentStageObservationService::class => AgentStageObservationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentStageObservationController::class => AgentStageObservationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentStageObservationForm::class => AgentStageObservationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentStageObservationHydrator::class => AgentStageObservationHydratorFactory::class,
        ],
    ]

];