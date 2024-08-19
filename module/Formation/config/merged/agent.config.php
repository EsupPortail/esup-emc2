<?php

namespace Formation;

use Formation\Controller\AgentController;
use Formation\Controller\AgentControllerFactory;
use Formation\Provider\Privilege\FormationagentPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'index',
                        'afficher-charte',
                        'valider-charte',
                    ],
                    'privilege' => [
                        FormationagentPrivileges::getResourceId(FormationagentPrivileges::FORMATIONAGENT_INDEX),
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'afficher',
                        'historique',
                    ],
                    'privilege' => [
                        FormationagentPrivileges::getResourceId(FormationagentPrivileges::FORMATIONAGENT_AFFICHER),
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'mes-agents',
                        'lister-mes-agents',
                    ],
                    'privilege' => [
                        FormationagentPrivileges::getResourceId(FormationagentPrivileges::FORMATIONAGENT_MESAGENTS),
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'mes-agents' => [
                        'order' => 200,
                        'label' => 'Mes agents',
                        'route' => 'formation/mes-agents',
                        'resource' => FormationagentPrivileges::getResourceId(FormationagentPrivileges::FORMATIONAGENT_MESAGENTS),
                        'dropdown-header' => true,
                        'pages' => [],
                    ],
                    'gestion-formation' => [
                        'pages' => [
                            'agent' => [
                                'order' => 420,
                                'label' => 'Agent',
                                'route' => 'formation/agent-index',
                                'resource' => FormationagentPrivileges::getResourceId(FormationagentPrivileges::FORMATIONAGENT_AFFICHER),
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
            'formation' => [
                'child_routes' => [
                    'charte' => [
                        'type'  => Literal::class,
                        'options' => [
                            /** @see AgentController::afficherCharteAction() */
                            'route'    => '/charte',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-charte',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'valider' => [
                                'type'  => Literal::class,
                                'options' => [
                                    /** @see AgentController::validerCharteAction() */
                                    'route'    => '/valider',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action'     => 'valider-charte',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'agent-index' => [
                        'type'  => Literal::class,
                        'options' => [
                            /** @see AgentController::indexAction() */
                            'route'    => '/agent-index',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see AgentController::afficherAction() */
                            'route'    => '/agent[/:agent]',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historique' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see AgentController::historiqueAction() */
                            'route'    => '/historique/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historique',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'mes-agents' => [
                        'type'  => Literal::class,
                        'options' => [
                            /** @see AgentController::mesAgentsAction() */
                            'route'    => '/mes-agents',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'mes-agents',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'lister' => [
                                'type'  => Literal::class,
                                'options' => [
                                    /** @see AgentController::indexAction() */
                                    'route'    => '/lister',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action'     => 'lister-mes-agents',
                                    ],
                                ],
                                'may_terminate' => true,
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
            AgentController::class => AgentControllerFactory::class,

        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];