<?php

namespace Formation;

use Formation\Controller\FormationThemeController;
use Formation\Controller\FormationThemeControllerFactory;
use Formation\Form\FormationTheme\FormationThemeForm;
use Formation\Form\FormationTheme\FormationThemeFormFactory;
use Formation\Form\FormationTheme\FormationThemeHydrator;
use Formation\Form\FormationTheme\FormationThemeHydratorFactory;
use Formation\Provider\Privilege\FormationthemePrivileges;
use Formation\Service\FormationTheme\FormationThemeService;
use Formation\Service\FormationTheme\FormationThemeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationThemeController::class,
                    'action' => [
                        'afficher-theme',
                    ],
                    'privileges' => [
                        FormationthemePrivileges::FORMATIONTHEME_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationThemeController::class,
                    'action' => [
                        'ajouter-theme',
                    ],
                    'privileges' => [
                        FormationthemePrivileges::FORMATIONTHEME_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationThemeController::class,
                    'action' => [
                        'editer-theme',
                    ],
                    'privileges' => [
                        FormationthemePrivileges::FORMATIONTHEME_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationThemeController::class,
                    'action' => [
                        'historiser-theme',
                        'restaurer-theme',
                    ],
                    'privileges' => [
                        FormationthemePrivileges::FORMATIONTHEME_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationThemeController::class,
                    'action' => [
                        'detruire-theme',
                    ],
                    'privileges' => [
                        FormationthemePrivileges::FORMATIONTHEME_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-theme' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-theme',
                    'defaults' => [
                        'controller' => FormationThemeController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-theme',
                            'defaults' => [
                                'controller' => FormationThemeController::class,
                                'action'     => 'afficher-theme',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationThemeController::class,
                                'action'     => 'ajouter-theme',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation-theme',
                            'defaults' => [
                                'controller' => FormationThemeController::class,
                                'action'     => 'editer-theme',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-theme',
                            'defaults' => [
                                'controller' => FormationThemeController::class,
                                'action'     => 'historiser-theme',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-theme',
                            'defaults' => [
                                'controller' => FormationThemeController::class,
                                'action'     => 'restaurer-theme',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation-theme',
                            'defaults' => [
                                'controller' => FormationThemeController::class,
                                'action'     => 'detruire-theme',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationThemeService::class => FormationThemeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationThemeController::class => FormationThemeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationThemeForm::class => FormationThemeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationThemeHydrator::class => FormationThemeHydratorFactory::class,
        ],
    ]

];