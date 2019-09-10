<?php

namespace Application;

use Indicateur\Controller\IndicateurController;
use Indicateur\Controller\IndicateurControllerFactory;
use Indicateur\Form\Indicateur\IndicateurForm;
use Indicateur\Form\Indicateur\IndicateurFormFactory;
use Indicateur\Form\Indicateur\IndicateurHydrator;
use Indicateur\Form\Indicateur\IndicateurHydratorFactory;
use Indicateur\Provider\Privilege\IndicateurPrivileges;
use Indicateur\Service\Indicateur\IndicateurService;
use Indicateur\Service\Indicateur\IndicateurServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Console\Router\Simple;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndicateurController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'rafraichir',
                        'creer',
                        'modifier',
                        'detruire',
                        'exporter',
                    ],
                    'privileges' => [
                        IndicateurPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => IndicateurController::class,
                    'action' => [
                        'rafraichir-console',
                    ],
                    'roles' => [],
                ],
            ],

        ],
    ],

    'router'          => [
        'routes' => [
            'indicateurs' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/indicateurs',
                    'defaults' => [
                        'controller' => IndicateurController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
            'indicateur' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/indicateur',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:indicateur',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'rafraichir' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/rafraichir/:indicateur',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'rafraichir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'creer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:indicateur',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:indicateur',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'exporter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/exporter/:indicateur',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'exporter',
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
                'indicateur-refresh' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'indicateur-refresh',
                        'defaults' => [
                            'controller' => IndicateurController::class,
                            'action' => 'rafraichir-console'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            IndicateurService::class => IndicateurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndicateurController::class => IndicateurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            IndicateurForm::class => IndicateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            IndicateurHydrator::class => IndicateurHydratorFactory::class,
        ],
    ]

];