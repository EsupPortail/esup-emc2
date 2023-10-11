<?php

namespace Formation;

use Formation\Controller\FormationConsoleController;
use Formation\Controller\FormationConsoleControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Unicaen\Console\Router\Simple;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationConsoleController::class,
                    'action' => [
                        'gerer-formations',
                        'notifier-convocation',
                        'notifier-questionnaire',
                        'cloturer-sessions',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'gerer-formations' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'formation-gerer-formations',
                        'defaults' => [
                            'controller' => FormationConsoleController::class,
                            'action' => 'gerer-formations'
                        ],
                    ],
                ],
                'notifier-convocation' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'formation-notifier-convocation',
                        'defaults' => [
                            'controller' => FormationConsoleController::class,
                            'action' => 'notifier-convocation'
                        ],
                    ],
                ],
                'notifier-questionnaire' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'formation-notifier-questionnaire',
                        'defaults' => [
                            'controller' => FormationConsoleController::class,
                            'action' => 'notifier-questionnaire'
                        ],
                    ],
                ],
                'cloturer-sessions' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'formation-cloturer-sessions',
                        'defaults' => [
                            'controller' => FormationConsoleController::class,
                            'action' => 'cloturer-sessions'
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
            FormationConsoleController::class => FormationConsoleControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];