<?php

namespace Application;

use Application\Controller\Affectation\AffectationController;
use Application\Controller\Affectation\AffectationControllerFactory;
use Application\Provider\Privilege\AffectationPrivileges;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AffectationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        AffectationPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => AffectationController::class,
                    'action' => [
                        'creer',
                        'editer',
                        'effacer',
                    ],
                    'privileges' => [
                        AffectationPrivileges::EDITER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'activite' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/affectation',
                    'defaults' => [
                        'controller' => AffectationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:id',
                            'defaults' => [
                                'controller' => AffectationController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer/:id',
                            'defaults' => [
                                'controller' => AffectationController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => AffectationController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            AffectationController::class => AffectationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'invokable' => [
        ]
    ]

];