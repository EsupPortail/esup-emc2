<?php

namespace Agent;

use Agent\Controller\AgentController;
use Agent\Controller\AgentControllerFactory;
use Agent\Provider\Privilege\AgentmobilitePrivileges;
use Agent\View\Helper\AgentOngletViewHelper;
use Agent\View\Helper\AgentOngletViewHelperFactory;
use Application\Assertion\AgentAssertion;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'acquis',
                        'informations',
                        'missions-specifiques',
                        'portfolio',
                    ],
                    'privileges' => [
                        //TODO change
                        AgentmobilitePrivileges::AGENTMOBILITE_AFFICHER,
                    ],
                    //'assertion' => AgentAssertion::class,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'agent' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/agent',
                ],
                'child_routes' => [
                    'acquis' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/acquis/:agent',
                            'defaults' => [
                                /** @see AgentController::acquisAction() */
                                'controller' => AgentController::class,
                                'action' => 'acquis'
                            ],
                        ],
                    ],
                    'informations' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/informations[/:agent]',
                            'defaults' => [
                                /** @see AgentController::informationsAction() */
                                'controller' => AgentController::class,
                                'action' => 'informations'
                            ],
                        ],
                    ],
                    'missions-specifiques' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/missions-specifiques/:agent',
                            'defaults' => [
                                /** @see AgentController::missionsSpecifiquesAction() */
                                'controller' => AgentController::class,
                                'action' => 'missions-specifiques'
                            ],
                        ],
                    ],
                    'portfolio' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/portfolio/:agent',
                            'defaults' => [
                                /** @see AgentController::portfolioAction() */
                                'controller' => AgentController::class,
                                'action' => 'portfolio'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers' => [
        'factories' => [
            AgentController::class => AgentControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],
    'view_helpers' => [
        'factories' => [
            AgentOngletViewHelper::class => AgentOngletViewHelperFactory::class,
        ],
        'aliases' => [
            'agentOnglet' => AgentOngletViewHelper::class,
        ],
    ],
];