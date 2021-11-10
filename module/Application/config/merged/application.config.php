<?php

namespace Application;

use Application\Controller\ApplicationController;
use Application\Controller\ApplicationControllerFactory;
use Application\Controller\ApplicationGroupeController;
use Application\Controller\ApplicationGroupeControllerFactory;
use Application\Form\Application\ApplicationForm;
use Application\Form\Application\ApplicationFormFactory;
use Application\Form\Application\ApplicationHydrator;
use Application\Form\Application\ApplicationHydratorFactory;
use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\ApplicationElement\ApplicationElementFormFactory;
use Application\Form\ApplicationElement\ApplicationElementHydrator;
use Application\Form\ApplicationElement\ApplicationElementHydratorFactory;
use Application\Form\ApplicationGroupe\ApplicationGroupeForm;
use Application\Form\ApplicationGroupe\ApplicationGroupeFormFactory;
use Application\Form\ApplicationGroupe\ApplicationGroupeHydrator;
use Application\Form\ApplicationGroupe\ApplicationGroupeHydratorFactory;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionApplication\SelectionApplicationFormFactory;
use Application\Form\SelectionApplication\SelectionApplicationHydrator;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\Application\ApplicationGroupeService;
use Application\Service\Application\ApplicationGroupeServiceFactory;
use Application\Service\Application\ApplicationService;
use Application\Service\Application\ApplicationServiceFactory;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\ApplicationElement\ApplicationElementServiceFactory;
use Application\Service\HasApplicationCollection\HasApplicationCollectionService;
use Application\Service\HasApplicationCollection\HasApplicationCollectionServiceFactory;
use Application\View\Helper\ApplicationBlocViewHelper;
use Application\View\Helper\ApplicationViewHelper;
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
                [
                  'controller' => ApplicationController::class,
                  'action' => [
                      'ajouter-application-element',
                  ],
                  'privileges' => [
                      AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                      FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                  ],
                ],
                [
                    'controller' => ApplicationGroupeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => ApplicationGroupeController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => ApplicationGroupeController::class,
                    'action' => [
                        'detruire-groupe',
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
                            'application' => [
                                'label' => 'Applications',
                                'route' => 'application',
                                'resource' => ApplicationPrivileges::getResourceId(ApplicationPrivileges::APPLICATION_AFFICHER),
                                'order' => 200,
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
                    'groupe' => [
                        'type' => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route' => '/groupe',
                            'defaults' => [
                                'controller' => ApplicationGroupeController::class,
                            ],
                        ],
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationGroupeController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => ApplicationGroupeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'editer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationGroupeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationGroupeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationGroupeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationGroupeController::class,
                                        'action' => 'detruire',
                                    ],
                                ],
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
        'invokables' => [
        ],
        'factories' => [
            ApplicationService::class => ApplicationServiceFactory::class,
            ApplicationElementService::class => ApplicationElementServiceFactory::class,
            ApplicationGroupeService::class => ApplicationGroupeServiceFactory::class,
            HasApplicationCollectionService::class => HasApplicationCollectionServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ApplicationController::class => ApplicationControllerFactory::class,
            ApplicationGroupeController::class => ApplicationGroupeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ApplicationForm::class => ApplicationFormFactory::class,
            ApplicationGroupeForm::class => ApplicationGroupeFormFactory::class,
            ApplicationElementForm::class => ApplicationElementFormFactory::class,
            SelectionApplicationForm::class => SelectionApplicationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            SelectionApplicationHydrator::class => SelectionApplicationHydrator::class,
        ],
        'factories' => [
            ApplicationHydrator::class => ApplicationHydratorFactory::class,
            ApplicationGroupeHydrator::class => ApplicationGroupeHydratorFactory::class,
            ApplicationElementHydrator::class => ApplicationElementHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'application' => ApplicationViewHelper::class,
            'applicationBloc' => ApplicationBlocViewHelper::class,
        ],
    ],

];