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
use Application\View\Helper\ActiviteViewHelper;
use UnicaenAuth\Guard\PrivilegeController;
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
                        'editer',
                        'historiser',
                        'restaurer',
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