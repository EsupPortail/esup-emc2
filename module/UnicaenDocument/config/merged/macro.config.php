<?php

use UnicaenDocument\Controller\MacroController;
use UnicaenDocument\Controller\MacroControllerFactory;
use UnicaenDocument\Form\Macro\MacroForm;
use UnicaenDocument\Form\Macro\MacroFormFactory;
use UnicaenDocument\Form\Macro\MacroHydrator;
use UnicaenDocument\Form\Macro\MacroHydratorFactory;
use UnicaenDocument\Provider\Privilege\DocumentmacroPrivileges;
use UnicaenDocument\Service\Macro\MacroService;
use UnicaenDocument\Service\Macro\MacroServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MacroController::class,
                    'action' => [
                        'index',
                        'generer-json'
                    ],
                    'privileges' => [
                        DocumentmacroPrivileges::DOCUMENTMACRO_INDEX,
                    ],
                ],
                [
                    'controller' => MacroController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        DocumentmacroPrivileges::DOCUMENTMACRO_AJOUTER,
                    ],
                ],
                [
                    'controller' => MacroController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        DocumentmacroPrivileges::DOCUMENTMACRO_MODIFIER,
                    ],
                ],
                [
                    'controller' => MacroController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        DocumentmacroPrivileges::DOCUMENTMACRO_HISTORISER,
                    ],
                ],
                [
                    'controller' => MacroController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        DocumentmacroPrivileges::DOCUMENTMACRO_SUPPRIMER,
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
                                    'macro' => [
                                        'label' => 'Macro',
                                        'route' => 'contenu/macro',
                                        'resource' => PrivilegeController::getResourceId(MacroController::class, 'index'),
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
                    'macro' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/macro',
                            'defaults' => [
                                'controller' => MacroController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'generer-json' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/generer-json',
                                    'defaults' => [
                                        'controller' => MacroController::class,
                                        'action' => 'generer-json',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => MacroController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:macro',
                                    'defaults' => [
                                        'controller' => MacroController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:macro',
                                    'defaults' => [
                                        'controller' => MacroController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:macro',
                                    'defaults' => [
                                        'controller' => MacroController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:macro',
                                    'defaults' => [
                                        'controller' => MacroController::class,
                                        'action' => 'supprimer',
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
            MacroService::class => MacroServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            MacroForm::class => MacroFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            MacroHydrator::class => MacroHydratorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MacroController::class => MacroControllerFactory::class,
        ]
    ],
];
