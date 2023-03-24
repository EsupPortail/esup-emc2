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
use Element\Form\SelectionApplication\SelectionApplicationHydratorFactory;
use Element\Provider\Privilege\ApplicationPrivileges;
use Element\Service\Application\ApplicationService;
use Element\Service\Application\ApplicationServiceFactory;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceFactory;
use Element\View\Helper\ApplicationBlocViewHelper;
use Element\View\Helper\ApplicationViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

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
                        'cartographie',
                        'exporter-cartographie',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_CARTOGRAPHIE,
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
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_HISTORISER,
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

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'label' => 'Applications',
                                'route' => 'element/application',
                                'resource' => PrivilegeController::getResourceId(ApplicationController::class, 'index') ,
                                'order' => 20200,
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
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'application' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/application',
                            'defaults' => [
                                /** @see ApplicationController::indexAction() */
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
                                        /** @see ApplicationController::cartographieAction() */
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
                                        /** @see ApplicationController::exporterCartographieAction() */
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
                                        /** @see ApplicationController::afficherAction() */
                                        'controller' => ApplicationController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'changer-status' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/changer-status/:id',
                                    'defaults' => [
                                        /** @see ApplicationController::changerStatusAction() */
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
                                        /** @see ApplicationController::editerAction() */
                                        'controller' => ApplicationController::class,
                                        'action' => 'editer',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:id',
                                    'defaults' => [
                                        /** @see ApplicationController::historiserAction() */
                                        'controller' => ApplicationController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:id',
                                    'defaults' => [
                                        /** @see ApplicationController::restaurerAction() */
                                        'controller' => ApplicationController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/effacer/:id',
                                    'defaults' => [
                                        /** @see ApplicationController::effacerAction() */
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
                                        /** @see ApplicationController::creerAction() */
                                        'controller' => ApplicationController::class,
                                        'action' => 'creer',
                                    ],
                                ],
                            ]
                        ],
                    ],
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
        'factories' => [
            ApplicationHydrator::class => ApplicationHydratorFactory::class,
            SelectionApplicationHydrator::class => SelectionApplicationHydratorFactory::class,

        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'application' => ApplicationViewHelper::class,
            'applicationBloc' => ApplicationBlocViewHelper::class,
        ],
    ],

];