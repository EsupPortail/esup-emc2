<?php

namespace Application;

use Application\Controller\Activite\ActiviteController;
use Application\Controller\Activite\ActiviteControllerFactory;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormFactory;
use Application\Form\Activite\ActiviteHydrator;
use Application\Provider\Privilege\ActivitePrivileges;
use Application\Service\Activite\ActiviteService;
use Application\Service\Activite\ActiviteServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ActivitePrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'creer',
                        'editer',
                        'effacer',
                    ],
                    'privileges' => [
                        ActivitePrivileges::EDITER,
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
                    'route'    => '/activite',
                    'defaults' => [
                        'controller' => ActiviteController::class,
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
                                'controller' => ActiviteController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer/:id',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => ActiviteController::class,
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
            ActiviteService::class => ActiviteServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ActiviteController::class => ActiviteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ActiviteForm::class => ActiviteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokable' => [
            ActiviteHydrator::class => ActiviteHydrator::class,
        ]
    ]

];