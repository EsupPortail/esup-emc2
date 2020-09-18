<?php

use UnicaenDocument\Controller\ContenuController;
use UnicaenDocument\Controller\ContenuControllerFactory;
use UnicaenDocument\Provider\Privilege\DocumentcontentPrivileges;
use UnicaenDocument\Service\Contenu\ContenuService;
use UnicaenDocument\Service\Contenu\ContenuServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_INDEX,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_AJOUTER,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_MODIFIER,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_HISTORISER,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'contenu' => [
                                'pages' => [
                                    'contenu' => [
                                        'label' => 'Contenu',
                                        'route' => 'contenu/contenu',
                                        'resource' => PrivilegeController::getResourceId(ContenuController::class, 'index'),
                                        'order'    => 10001,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'contenu' => [
                'child_routes' => [
                    'contenu' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contenu',
                            'defaults' => [
                                'controller' => ContenuController::class,
                                'action' => 'index',
                            ],
                        ],
//                        'may_terminate' => true,
//                        'child_routes' => [
//                            'generer-json' => [
//                                'type' => Literal::class,
//                                'options' => [
//                                    'route' => '/generer-json',
//                                    'defaults' => [
//                                        'controller' => MacroController::class,
//                                        'action' => 'generer-json',
//                                    ],
//                                ],
//                            ],
//                            'ajouter' => [
//                                'type' => Literal::class,
//                                'options' => [
//                                    'route' => '/ajouter',
//                                    'defaults' => [
//                                        'controller' => MacroController::class,
//                                        'action' => 'ajouter',
//                                    ],
//                                ],
//                            ],
//                            'modifier' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/modifier/:macro',
//                                    'defaults' => [
//                                        'controller' => MacroController::class,
//                                        'action' => 'modifier',
//                                    ],
//                                ],
//                            ],
//                            'historiser' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/historiser/:macro',
//                                    'defaults' => [
//                                        'controller' => MacroController::class,
//                                        'action' => 'historiser',
//                                    ],
//                                ],
//                            ],
//                            'restaurer' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/restaurer/:macro',
//                                    'defaults' => [
//                                        'controller' => MacroController::class,
//                                        'action' => 'restaurer',
//                                    ],
//                                ],
//                            ],
//                            'supprimer' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/supprimer/:macro',
//                                    'defaults' => [
//                                        'controller' => MacroController::class,
//                                        'action' => 'supprimer',
//                                    ],
//                                ],
//                            ],
//                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ContenuService::class => ContenuServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
//            MacroForm::class => MacroFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
//            MacroHydrator::class => MacroHydratorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ContenuController::class => ContenuControllerFactory::class,
        ]
    ],
];
