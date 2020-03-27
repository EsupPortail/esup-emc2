<?php

namespace Application;

use Indicateur\Controller\AbonnementController;
use Indicateur\Controller\AbonnementControllerFactory;
use Indicateur\Provider\Privilege\AbonnementPrivileges;
use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Abonnement\AbonnementServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Mvc\Console\Router\Simple;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AbonnementController::class,
                    'action' => [
                        'souscrire',
                        'resilier',
                        'notifier'
                    ],
                    'privileges' => [
                        AbonnementPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => AbonnementController::class,
                    'action' => [
                        'notifier-console'
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'abonnement' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/abonnement',
                    'defaults' => [
                        'controller' => AbonnementController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'souscrire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/souscrire/:indicateur',
                            'defaults' => [
                                'controller' => AbonnementController::class,
                                'action' => 'souscrire'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'resilier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/resilier/:indicateur',
                            'defaults' => [
                                'controller' => AbonnementController::class,
                                'action' => 'resilier'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'notifier' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/notifier',
                            'defaults' => [
                                'controller' => AbonnementController::class,
                                'action' => 'notifier'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'indicateur-notifier' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'indicateur-notifier',
                        'defaults' => [
                            'controller' => AbonnementController::class,
                            'action' => 'notifier-console'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            AbonnementService::class => AbonnementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AbonnementController::class => AbonnementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];