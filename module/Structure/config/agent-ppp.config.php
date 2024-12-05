<?php

namespace Application;

use AgentPppController;
use AgentPppControllerFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Form\AgentPPP\AgentPPPForm;
use Form\AgentPPP\AgentPPPFormFactory;
use Form\AgentPPP\AgentPPPHydrator;
use Form\AgentPPP\AgentPPPHydratorFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Service\AgentPPP\AgentPPPService;
use Service\AgentPPP\AgentPPPServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentPppController::class,
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
                    'ppp' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ppp',
                            'defaults' => [
                                'controller' => AgentPppController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentPppController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:ppp',
                                    'defaults' => [
                                        'controller' => AgentPppController::class,
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:ppp',
                                    'defaults' => [
                                        'controller' => AgentPppController::class,
                                        'action' => 'historiser'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:ppp',
                                    'defaults' => [
                                        'controller' => AgentPppController::class,
                                        'action' => 'restaurer'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:ppp',
                                    'defaults' => [
                                        'controller' => AgentPppController::class,
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
            AgentPPPService::class => AgentPPPServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentPppController::class => AgentPppControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentPPPForm::class => AgentPPPFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentPPPHydrator::class => AgentPPPHydratorFactory::class,
        ],
    ]

];