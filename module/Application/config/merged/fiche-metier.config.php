<?php

namespace Application;

use Application\Controller\FicheMetier\FicheMetierController;
use Application\Controller\FicheMetier\FicheMetierControllerFactory;
use Application\Form\FicheMetier\FicheMetierCreationForm;
use Application\Form\FicheMetier\FicheMetierCreationFormFactory;
use Application\Form\FicheMetier\FicheMetierCreationHydrator;
use Application\Form\FicheMetier\FicheMetierCreationHydratorFactory;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetier\FicheMetierServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::HISTORISER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'editer',
                        'creer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::EDITER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                    'defaults' => [
                        'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:id/:section',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'fiche-metier' => [
                        'order' => -10,
                        'label' => 'Fiche métier',
                        'title' => "Fiche métier",
                        'route' => 'fiche-metier',
                        'roles' => [], //PrivilegeController::getResourceId(__NAMESPACE__ . '\Controller\Administration', 'index'),
                        'pages' => [
//                            [
//                                'label' => "Droits et privilèges",
//                                'route' => 'droits',
//                                'roles' => [],//'resource' => PrivilegeController::getResourceId('UnicaenAuth\Controller\Droits', 'index'),
//                                'dropdown-header' => true,
//                                'icon' => 'fas fa-user',
//                            ],
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
            FicheMetierService::class => FicheMetierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FicheMetierCreationForm::class => FicheMetierCreationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FicheMetierCreationHydrator::class => FicheMetierCreationHydratorFactory::class,
        ]
    ]

];