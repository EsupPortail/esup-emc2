<?php

namespace Element;

use Element\Controller\NiveauController;
use Element\Controller\NiveauControllerFactory;
use Element\Form\Niveau\NiveauForm;
use Element\Form\Niveau\NiveauFormFactory;
use Element\Form\Niveau\NiveauHydrator;
use Element\Form\Niveau\NiveauHydratorFactory;
use Element\Provider\Privilege\NiveauPrivileges;
use Element\Service\Niveau\NiveauService;
use Element\Service\Niveau\NiveauServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_INDEX,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_AFFICHER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_MODIFIER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        NiveauPrivileges::NIVEAU_EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'niveau' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => NiveauController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:maitrise',
                                    'defaults' => [
                                        'controller' => NiveauController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:maitrise',
                                    'defaults' => [
                                        'controller' => NiveauController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:maitrise',
                                    'defaults' => [
                                        'controller' => NiveauController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:maitrise',
                                    'defaults' => [
                                        'controller' => NiveauController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:maitrise',
                                    'defaults' => [
                                        'controller' => NiveauController::class,
                                        'action'     => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
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
                                'label' => 'Niveaux',
                                'route' => 'element/niveau',
                                'resource' => PrivilegeController::getResourceId(NiveauController::class, 'index') ,
                                'order' => 280,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            NiveauService::class => NiveauServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            NiveauController::class => NiveauControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            NiveauForm::class => NiveauFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            NiveauHydrator::class => NiveauHydratorFactory::class,
        ],
    ]

];