<?php

namespace Application;

use Indicateur\Controller\Indicateur\IndicateurController;
use Indicateur\Controller\Indicateur\IndicateurControllerFactory;
use Indicateur\Provider\Privilege\IndicateurPrivileges;
use Indicateur\Service\Indicateur\IndicateurService;
use Indicateur\Service\Indicateur\IndicateurServiceFactory;
use Indicateur\View\Helper\IndicateurStructureViewHelper;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

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
                    ],
                    'privileges' => [
                        IndicateurPrivileges::AFFICHER,
                    ],
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
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/indicateur/:indicateur',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/afficher',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'rafraichir' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rafraichir',
                            'defaults' => [
                                'controller' => IndicateurController::class,
                                'action'     => 'rafraichir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ]
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
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ],
    'view_helpers' => [
        'invokables' => [
            'indicateurStructure' => IndicateurStructureViewHelper::class,
        ]
    ]

];