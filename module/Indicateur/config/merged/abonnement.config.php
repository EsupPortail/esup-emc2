<?php

namespace Application;

use Indicateur\Controller\Abonnement\AbonnementController;
use Indicateur\Controller\Abonnement\AbonnementControllerFactory;
use Indicateur\Provider\Privilege\AbonnementPrivileges;
use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Abonnement\AbonnementServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

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
                            'route'    => '/resilier/:abonnement',
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