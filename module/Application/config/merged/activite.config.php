<?php

namespace Application;

use Application\Controller\ActiviteController;
use Application\Controller\ActiviteControllerFactory;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormFactory;
use Application\Form\Activite\ActiviteHydrator;
use Application\Form\Activite\ActiviteHydratorFactory;
use Application\Provider\Privilege\ActivitePrivileges;
use Application\Service\Activite\ActiviteService;
use Application\Service\Activite\ActiviteServiceFactory;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceFactory;
use Application\View\Helper\ActiviteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        ActivitePrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'creer',
                    ],
                    'privileges' => [
                        ActivitePrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'modifier',
                        'convert',
                        'historiser',
                        'restaurer',
                        'modifier-application',
                        'modifier-competence',
                        'modifier-formation',
                    ],
                    'privileges' => [
                        ActivitePrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => ActiviteController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        ActivitePrivileges::EFFACER,
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
                            'mission-pincipale' => [
                                'label'    => 'Missions principales',
                                'route'    => 'activite',
                                'resource' => ActivitePrivileges::getResourceId(ActivitePrivileges::AFFICHER),
                                'order'    => 1000,
                            ],
                        ],
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
                    'route'    => '/mission-principale',
                    'defaults' => [
                        'controller' => ActiviteController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier[/:activite]',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'convert' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/convert/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'convert',
                            ],
                        ],
                    ],
                    'modifier-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-application/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-application',
                            ],
                        ],
                    ],
                    'modifier-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-competence/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-competence',
                            ],
                        ],
                    ],
                    'modifier-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formation/:activite',
                            'defaults' => [
                                'controller' => ActiviteController::class,
                                'action'     => 'modifier-formation',
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
        'factories' => [
            ActiviteService::class => ActiviteServiceFactory::class,
            ActiviteDescriptionService::class => ActiviteDescriptionServiceFactory::class,
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
        'factories' => [
            ActiviteHydrator::class => ActiviteHydratorFactory::class,
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'activite'  => ActiviteViewHelper::class,
        ],
    ],

];