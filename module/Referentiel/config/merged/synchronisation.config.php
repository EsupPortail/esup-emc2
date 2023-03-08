<?php

namespace Carriere;

use Laminas\Mvc\Console\Router\Simple;
use Referentiel\Controller\SynchronisationConsoleController;
use Referentiel\Controller\SynchronisationConsoleControllerFactory;
use Referentiel\Service\Synchronisation\SynchronisationService;
use Referentiel\Service\Synchronisation\SynchronisationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SynchronisationConsoleController::class,
                    'action' => [
                        'synchroniser-all',
                        'synchroniser',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'synchroniser-all' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'synchroniser-all',
                        'defaults' => [
                            /** @see SynchronisationConsoleController::synchroniserAllAction() */
                            'controller' => SynchronisationConsoleController::class,
                            'action' => 'synchroniser-all'
                        ],
                    ],
                ],
                'synchroniser' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'synchroniser [--name=]',
                        'defaults' => [
                            /** @see SynchronisationConsoleController::synchroniserAction() */
                            'controller' => SynchronisationConsoleController::class,
                            'action' => 'synchroniser'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            SynchronisationService::class => SynchronisationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            SynchronisationConsoleController::class => SynchronisationConsoleControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];