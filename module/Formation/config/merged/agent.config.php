<?php

namespace Formation;

use Application\Controller\AgentController;
use Formation\Provider\Privilege\FormationagentPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [

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
                                'route' => 'formation/agent',
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
                    'agent-index' => [
                        'type'  => Literal::class,
                        'options' => [
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
                            'route'    => '/agent[/:agent]',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'mes-agents' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/mes-agents',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'mes-agents',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];