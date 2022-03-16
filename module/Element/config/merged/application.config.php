<?php

namespace Element;

use Element\Controller\ApplicationController;
use Element\Controller\ApplicationControllerFactory;
use Element\Form\Application\ApplicationForm;
use Element\Form\Application\ApplicationFormFactory;
use Element\Form\Application\ApplicationHydrator;
use Element\Form\Application\ApplicationHydratorFactory;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionApplication\SelectionApplicationFormFactory;
use Element\Form\SelectionApplication\SelectionApplicationHydrator;
use Element\Provider\Privilege\ApplicationPrivileges;
use Element\Service\Application\ApplicationService;
use Element\Service\Application\ApplicationServiceFactory;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceFactory;
use Element\View\Helper\ApplicationBlocViewHelper;
use Element\View\Helper\ApplicationViewHelper;
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
                        'cartographie',
                        'exporter-cartographie',
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
                        ApplicationPrivileges::APPLICATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'creer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_MODIFIER,
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
                [
                    'controller' => ApplicationController::class,
                    'action' => [
                        'ajouter-application-element',
                    ],
                    'privileges' => [
//                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
//                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'label' => 'Applications',
                                'route' => 'application',
                                'resource' => PrivilegeController::getResourceId(ApplicationController::class, 'index') ,
                                'order' => 220,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'application' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/application',
                    'defaults' => [
                        'controller' => ApplicationController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'cartographie' => [
                        'type' => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route' => '/cartographie',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'cartographie'
                            ],
                        ],
                    ],
                    'exporter-cartographie' => [
                        'type' => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route' => '/exporter-cartographie',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'exporter-cartographie'
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter-application-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-application-element/:type/:id[/:clef]',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action'     => 'ajouter-application-element',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'changer-status' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/changer-status/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'changer-status',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/editer/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'editer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/effacer/:id',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'effacer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/creer',
                            'defaults' => [
                                'controller' => ApplicationController::class,
                                'action' => 'creer',
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ApplicationService::class => ApplicationServiceFactory::class,
            HasApplicationCollectionService::class => HasApplicationCollectionServiceFactory::class,
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
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'application' => ApplicationViewHelper::class,
            'applicationBloc' => ApplicationBlocViewHelper::class,
        ],
    ],

];