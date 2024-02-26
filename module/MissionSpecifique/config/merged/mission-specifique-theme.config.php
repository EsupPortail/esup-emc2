<?php

namespace MissionSpecifique;

use MissionSpecifique\Controller\MissionSpecifiqueThemeController;
use MissionSpecifique\Controller\MissionSpecifiqueThemeControllerFactory;
use MissionSpecifique\Provider\Privilege\MissionspecifiquethemePrivileges;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MissionSpecifiqueThemeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        MissionspecifiquethemePrivileges::MISSIONSPECIFIQUETHEME_INDEX,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueThemeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        MissionspecifiquethemePrivileges::MISSIONSPECIFIQUETHEME_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueThemeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MissionspecifiquethemePrivileges::MISSIONSPECIFIQUETHEME_AJOUTER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueThemeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        MissionspecifiquethemePrivileges::MISSIONSPECIFIQUETHEME_MODIFIER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueThemeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MissionspecifiquethemePrivileges::MISSIONSPECIFIQUETHEME_HISTORISER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueThemeController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        MissionspecifiquethemePrivileges::MISSIONSPECIFIQUETHEME_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'mission-specifique-theme' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mission-specifique-theme',
                    'defaults' => [
                        /** @see MissionSpecifiqueThemeController::indexAction() */
                        'controller' => MissionSpecifiqueThemeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:theme',
                            'defaults' => [
                                /** @see MissionSpecifiqueThemeController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see MissionSpecifiqueThemeController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:theme',
                            'defaults' => [
                                /** @see MissionSpecifiqueThemeController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:theme',
                            'defaults' => [
                                /** @see MissionSpecifiqueThemeController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:theme',
                            'defaults' => [
                                /** @see MissionSpecifiqueThemeController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:theme',
                            'defaults' => [
                                /** @see MissionSpecifiqueThemeController::detruireAction() */
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            MissionSpecifiqueThemeService::class => MissionSpecifiqueThemeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MissionSpecifiqueThemeController::class => MissionSpecifiqueThemeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];