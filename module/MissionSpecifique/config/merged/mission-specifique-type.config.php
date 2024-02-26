<?php

namespace MissionSpecifique;

use MissionSpecifique\Controller\MissionSpecifiqueTypeController;
use MissionSpecifique\Controller\MissionSpecifiqueTypeControllerFactory;
use MissionSpecifique\Provider\Privilege\MissionspecifiquetypePrivileges;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MissionSpecifiqueTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        MissionspecifiquetypePrivileges::MISSIONSPECIFIQUETYPE_INDEX,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueTypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        MissionspecifiquetypePrivileges::MISSIONSPECIFIQUETYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MissionspecifiquetypePrivileges::MISSIONSPECIFIQUETYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        MissionspecifiquetypePrivileges::MISSIONSPECIFIQUETYPE_MODIFIER,
                    ],
                ],
                [
                'controller' => MissionSpecifiqueTypeController::class,
                'action' => [
                    'historiser',
                    'restaurer',
                ],
                'privileges' => [
                    MissionspecifiquetypePrivileges::MISSIONSPECIFIQUETYPE_HISTORISER,
                ],
            ],
                [
                    'controller' => MissionSpecifiqueTypeController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        MissionspecifiquetypePrivileges::MISSIONSPECIFIQUETYPE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'mission-specifique-type' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mission-specifique-type',
                    'defaults' => [
                        /** @see MissionSpecifiqueTypeController::indexAction() */
                        'controller' => MissionSpecifiqueTypeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:type',
                            'defaults' => [
                                /** @see MissionSpecifiqueTypeController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see MissionSpecifiqueTypeController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:type',
                            'defaults' => [
                                /** @see MissionSpecifiqueTypeController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:type',
                            'defaults' => [
                                /** @see MissionSpecifiqueTypeController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:type',
                            'defaults' => [
                                /** @see MissionSpecifiqueTypeController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:type',
                            'defaults' => [
                                /** @see MissionSpecifiqueTypeController::detruireAction() */
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
            MissionSpecifiqueTypeService::class => MissionSpecifiqueTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MissionSpecifiqueTypeController::class => MissionSpecifiqueTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];