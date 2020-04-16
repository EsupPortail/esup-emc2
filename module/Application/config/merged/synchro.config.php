<?php

namespace Application;

use Application\Controller\SynchroController;
use Application\Controller\SynchroControllerFactory;
use Application\Provider\Privilege\SynchroPrivileges;
use Application\Service\Synchro\SynchroService;
use Application\Service\Synchro\SynchroServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SynchroController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        SynchroPrivileges::SYNCHRO_AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'synchro' => [
                                'label'    => 'Synchronisation',
                                'route'    => 'synchro',
                                'resource' => SynchroPrivileges::getResourceId(SynchroPrivileges::SYNCHRO_AFFICHER),
                                'order'    => 1000,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'synchro' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/synchro',
                    'defaults' => [
                        'controller' => SynchroController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            SynchroService::class => SynchroServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            SynchroController::class => SynchroControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]
];