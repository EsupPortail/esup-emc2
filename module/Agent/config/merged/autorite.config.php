<?php

namespace Application;

use Agent\Controller\AutoriteController;
use Agent\Controller\AutoriteControllerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AutoriteController::class,
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
            'agent-autorite' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/agent-autorite',
                    'defaults' => [
                        'controller' => AutoriteController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'agents' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/agents[/:agent]',
                            'defaults' => [
                                /** @see AutoriteController::agentsAction() */
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
        AutoriteController::class => AutoriteControllerFactory::class,
    ],
],
    'form_elements' => [
    'factories' => [],
],
    'hydrators' => [
    'factories' => [],
]

];