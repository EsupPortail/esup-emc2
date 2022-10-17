<?php

namespace Application;

use Application\Controller\MissionSpecifiqueTypeController;
use Application\Controller\MissionSpecifiqueTypeControllerFactory;
use Application\Provider\Privilege\MissionspecifiquetypePrivileges;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeServiceFactory;
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
                                'controller' => MissionSpecifiqueTypeController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => MissionSpecifiqueTypeController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:type',
                            'defaults' => [
                                'controller' => MissionSpecifiqueTypeController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:type',
                            'defaults' => [
                                'controller' => MissionSpecifiqueTypeController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:type',
                            'defaults' => [
                                'controller' => MissionSpecifiqueTypeController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:type',
                            'defaults' => [
                                'controller' => MissionSpecifiqueTypeController::class,
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