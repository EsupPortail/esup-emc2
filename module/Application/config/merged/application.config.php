<?php

namespace Application;

use Application\Controller\ApplicationController;
use Application\Controller\ApplicationControllerFactory;
use Application\Form\Application\ApplicationForm;
use Application\Form\Application\ApplicationFormFactory;
use Application\Form\Application\ApplicationHydrator;
use Application\Form\Application\ApplicationHydratorFactory;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionApplication\SelectionApplicationFormFactory;
use Application\Form\SelectionApplication\SelectionApplicationHydrator;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Service\Application\ApplicationService;
use Application\Service\Application\ApplicationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_INDEX,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'changer-status',
                        'editer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_EDITER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'creer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'application' => [
                                'label'    => 'Applications',
                                'route'    => 'application',
                                'resource' => ApplicationPrivileges::getResourceId(ApplicationPrivileges::APPLICATION_AFFICHER),
                                'order'    => 200,
                            ],
                        ],
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
            SelectionApplicationForm::class => SelectionApplicationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            SelectionApplicationHydrator::class => SelectionApplicationHydrator::class,
        ],
        'factories' => [
            ApplicationHydrator::class => ApplicationHydratorFactory::class,
        ]
    ]

];