<?php

namespace Application;

use Application\Controller\Metier\MetierController;
use Application\Controller\Metier\MetierControllerFactory;
use Application\Form\Metier\MetierForm;
use Application\Form\Metier\MetierFormFactory;
use Application\Form\Metier\MetierHydrator;
use Application\Provider\Privilege\MetierPrivileges;
use Application\Service\Metier\MetierService;
use Application\Service\Metier\MetierServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        MetierPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'editer',
                    ],
                    'privileges' => [
                        MetierPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'creer',
                    ],
                    'privileges' => [
                        MetierPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        MetierPrivileges::EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/metier',
                    'defaults' => [
                        'controller' => MetierController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:id',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer/:id',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            MetierService::class => MetierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MetierController::class => MetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            MetierForm::class => MetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokable' => [
            MetierHydrator::class => MetierHydrator::class,
        ]
    ]

];