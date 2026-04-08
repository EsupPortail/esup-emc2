<?php

namespace Agent;

use Agent\Assertion\AgentAssertion;
use Agent\Assertion\AgentAssertionFactory;
use Agent\Controller\AgentController;
use Agent\Controller\AgentControllerFactory;
use Agent\Provider\Privilege\AgentPrivileges;
use Agent\View\Helper\AgentOngletViewHelper;
use Agent\View\Helper\AgentOngletViewHelperFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Agent' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            AgentPrivileges::AGENT_AFFICHER,
//                            AgentPrivileges::AGENT_ELEMENT_VOIR,
//                            AgentPrivileges::AGENT_ELEMENT_AJOUTER,
//                            AgentPrivileges::AGENT_ELEMENT_MODIFIER,
//                            AgentPrivileges::AGENT_ELEMENT_HISTORISER,
//                            AgentPrivileges::AGENT_ELEMENT_DETRUIRE,
//                            AgentPrivileges::AGENT_ELEMENT_VALIDER,
//                            AgentPrivileges::AGENT_ACQUIS_AFFICHER,
//                            AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                        ],
                        'resources' => ['Agent'],
                        'assertion' => AgentAssertion::class
                    ],
//                    [
//                        'privileges' => [
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_SUPERIEUR,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_AUTORITE,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_COMPTE,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_CARRIERECOMPLETE,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_DATERESUME,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_AFFECTATION,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_STATUT,
//                        ],
//                        'resources' => ['Agent'],
//                        'assertion' => AgentAffichageAssertion::class
//                    ],
                ],
            ],
        ],
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
                        AgentPrivileges::AGENT_AFFICHER,
                    ],
                    'assertion' => AgentAssertion::class,
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
            AgentAssertion::class => AgentAssertionFactory::class,
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