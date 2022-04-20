<?php

namespace Application;

use Application\Controller\MissionSpecifiqueThemeController;
use Application\Controller\MissionSpecifiqueThemeControllerFactory;
use Application\Provider\Privilege\MissionspecifiquethemePrivileges;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                                'controller' => MissionSpecifiqueThemeController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => MissionSpecifiqueThemeController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:theme',
                            'defaults' => [
                                'controller' => MissionSpecifiqueThemeController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:theme',
                            'defaults' => [
                                'controller' => MissionSpecifiqueThemeController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:theme',
                            'defaults' => [
                                'controller' => MissionSpecifiqueThemeController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:theme',
                            'defaults' => [
                                'controller' => MissionSpecifiqueThemeController::class,
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