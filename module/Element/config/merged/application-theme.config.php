<?php

namespace Element;

use Element\Controller\ApplicationThemeController;
use Element\Controller\ApplicationThemeControllerFactory;
use Element\Form\ApplicationTheme\ApplicationThemeForm;
use Element\Form\ApplicationTheme\ApplicationThemeFormFactory;
use Element\Form\ApplicationTheme\ApplicationThemeHydrator;
use Element\Form\ApplicationTheme\ApplicationThemeHydratorFactory;
use Element\Provider\Privilege\ApplicationthemePrivileges;
use Element\Service\ApplicationTheme\ApplicationThemeService;
use Element\Service\ApplicationTheme\ApplicationThemeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ApplicationThemeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ApplicationthemePrivileges::APPLICATIONTHEME_INDEX,
                    ],
                ],
                [
                    'controller' => ApplicationThemeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationthemePrivileges::APPLICATIONTHEME_AFFICHER,
                    ],
                ],
                [
                    'controller' => ApplicationThemeController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ApplicationthemePrivileges::APPLICATIONTHEME_MODIFIER,
                    ],
                ],
                [
                    'controller' => ApplicationThemeController::class,
                    'action' => [
                        'detruire-groupe',
                    ],
                    'privileges' => [
                        ApplicationthemePrivileges::APPLICATIONTHEME_EFFACER,
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
                    'application-theme' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/application-theme',
                            'defaults' => [
                                'controller' => ApplicationThemeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationThemeController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => ApplicationThemeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'editer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationThemeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationThemeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationThemeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:application-groupe',
                                    'defaults' => [
                                        'controller' => ApplicationThemeController::class,
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
            ApplicationThemeService::class => ApplicationThemeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ApplicationThemeController::class => ApplicationThemeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ApplicationThemeForm::class => ApplicationThemeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ApplicationThemeHydrator::class => ApplicationThemeHydratorFactory::class,
        ],
    ]

];