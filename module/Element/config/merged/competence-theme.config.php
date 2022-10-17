<?php

namespace Element;

use Element\Controller\CompetenceThemeController;
use Element\Controller\CompetenceThemeControllerFactory;
use Element\Form\CompetenceTheme\CompetenceThemeForm;
use Element\Form\CompetenceTheme\CompetenceThemeFormFactory;
use Element\Form\CompetenceTheme\CompetenceThemeHydrator;
use Element\Form\CompetenceTheme\CompetenceThemeHydratorFactory;
use Element\Provider\Privilege\CompetencethemePrivileges;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceTheme\CompetenceThemeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceThemeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencethemePrivileges::COMPETENCETHEME_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceThemeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CompetencethemePrivileges::COMPETENCETHEME_AFFICHER,
                    ],
                ],
                [
                    'controller' => CompetenceThemeController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CompetencethemePrivileges::COMPETENCETHEME_MODIFIER,
                    ],
                ],
                [
                    'controller' => CompetenceThemeController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        CompetencethemePrivileges::COMPETENCETHEME_EFFACER,
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
                    'competence-theme' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence-theme',
                            'defaults' => [
                                'controller' => CompetenceThemeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:competence-theme',
                                    'defaults' => [
                                        'controller' => CompetenceThemeController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => CompetenceThemeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:competence-theme',
                                    'defaults' => [
                                        'controller' => CompetenceThemeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:competence-theme',
                                    'defaults' => [
                                        'controller' => CompetenceThemeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:competence-theme',
                                    'defaults' => [
                                        'controller' => CompetenceThemeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:competence-theme',
                                    'defaults' => [
                                        'controller' => CompetenceThemeController::class,
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
            CompetenceThemeService::class => CompetenceThemeServiceFactory::class
        ],
    ],
    'controllers'     => [
        'factories' => [
            CompetenceThemeController::class => CompetenceThemeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceThemeForm::class => CompetenceThemeFormFactory::class
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceThemeHydrator::class => CompetenceThemeHydratorFactory::class,
        ],
    ]

];