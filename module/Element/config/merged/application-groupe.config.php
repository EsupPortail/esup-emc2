<?php

namespace Element;

use Element\Controller\ApplicationGroupeController;
use Element\Controller\ApplicationGroupeControllerFactory;
use Element\Form\ApplicationGroupe\ApplicationGroupeForm;
use Element\Form\ApplicationGroupe\ApplicationGroupeFormFactory;
use Element\Form\ApplicationGroupe\ApplicationGroupeHydrator;
use Element\Form\ApplicationGroupe\ApplicationGroupeHydratorFactory;
use Element\Provider\Privilege\ApplicationGroupePrivileges;
use Element\Service\ApplicationGroupe\ApplicationGroupeService;
use Element\Service\ApplicationGroupe\ApplicationGroupeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ApplicationGroupeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationGroupePrivileges::APPLICATIONGROUPE_AFFICHER,
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
                        ApplicationGroupePrivileges::APPLICATIONGROUPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ApplicationGroupeController::class,
                    'action' => [
                        'detruire-groupe',
                    ],
                    'privileges' => [
                        ApplicationGroupePrivileges::APPLICATIONGROUPE_EFFACER,
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
//                    'defaults' => [
//                        'controller' => ApplicationController::class,
//                        'action' => 'index',
//                    ],
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
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ApplicationGroupeService::class => ApplicationGroupeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ApplicationGroupeController::class => ApplicationGroupeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ApplicationGroupeForm::class => ApplicationGroupeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ApplicationGroupeHydrator::class => ApplicationGroupeHydratorFactory::class,
        ],
    ]

];