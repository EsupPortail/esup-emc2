<?php

namespace Application;

use Application\Controller\Affectation\AffectationController;
use Application\Controller\Affectation\AffectationControllerFactory;
use Application\Form\Affectation\AffectationForm;
use Application\Form\Affectation\AffectationFormFactory;
use Application\Form\Affectation\AffectationHydrator;
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
                    ],
                    'privileges' => [
                        AffectationPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => AffectationController::class,
                    'action' => [
                        'editer',
                    ],
                    'privileges' => [
                        AffectationPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => AffectationController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        AffectationPrivileges::EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'affectation' => [
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
            AffectationForm::class => AffectationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokable' => [
            AffectationHydrator::class => AffectationHydrator::class,
        ]
    ]

];