<?php

namespace Application;

use Application\Controller\Application\ApplicationController;
use Application\Controller\Application\ApplicationControllerFactory;
use Application\Form\Application\ApplicationForm;
use Application\Form\Application\ApplicationFormFactory;
use Application\Form\Application\ApplicationHydrator;
use Application\Form\Application\ApplicationHydratorFactory;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Service\Application\ApplicationService;
use Application\Service\Application\ApplicationServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'changer-status',
                        'editer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'creer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'application' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/application',
                    'defaults' => [
                        'controller' => ApplicationController::class,
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
                                'controller' => ApplicationController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'changer-status' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/changer-status/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action'     => 'changer-status',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => ApplicationController::class,
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
            ApplicationService::class => ApplicationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ApplicationController::class => ApplicationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ApplicationForm::class => ApplicationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ApplicationHydrator::class => ApplicationHydratorFactory::class,
        ]
    ]

];