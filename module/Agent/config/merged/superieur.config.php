<?php

namespace Application;

use Agent\Controller\SuperieurController;
use Agent\Controller\SuperieurControllerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SuperieurController::class,
                    'action' => [
                        'agents',
                    ],
                    'roles' => [
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'agent-superieur' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/agent-superieur',
                    'defaults' => [
                        'controller' => SuperieurController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'agents' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/agents[/:agent]',
                            'defaults' => [
                                /** @see SuperieurController::agentsAction() */
                                'action' => 'agents',
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
            SuperieurController::class => SuperieurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];