<?php

namespace Application;

use Application\Controller\AgentTutoratController;
use Application\Controller\AgentTutoratControllerFactory;
use Application\Form\AgentTutorat\AgentTutoratForm;
use Application\Form\AgentTutorat\AgentTutoratFormFactory;
use Application\Form\AgentTutorat\AgentTutoratHydrator;
use Application\Form\AgentTutorat\AgentTutoratHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\AgentTutorat\AgentTutoratService;
use Application\Service\AgentTutorat\AgentTutoratServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentTutoratController::class,
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
                    'tutorat' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/stage-tutorat',
                            'defaults' => [
                                'controller' => AgentTutoratController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentTutoratController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentTutoratController::class,
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentTutoratController::class,
                                        'action' => 'historiser'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentTutoratController::class,
                                        'action' => 'restaurer'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentTutoratController::class,
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
            AgentTutoratService::class => AgentTutoratServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentTutoratController::class => AgentTutoratControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentTutoratForm::class => AgentTutoratFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentTutoratHydrator::class => AgentTutoratHydratorFactory::class,
        ],
    ]

];