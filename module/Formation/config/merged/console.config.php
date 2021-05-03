<?php

namespace Formation;

use Application\Controller\EtudiantController;
use Formation\Controller\FormationConsoleController;
use Formation\Controller\FormationConsoleControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Mvc\Console\Router\Simple;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationConsoleController::class,
                    'action' => [
                        'notifier-convocation',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'etudiant-refresh' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'formation-notifier-convocation',
                        'defaults' => [
                            'controller' => FormationConsoleController::class,
                            'action' => 'notifier-convocation'
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