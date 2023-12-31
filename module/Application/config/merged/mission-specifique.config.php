<?php

namespace Application;

use Application\Controller\MissionSpecifiqueController;
use Application\Controller\MissionSpecifiqueControllerFactory;
use Application\Form\MissionSpecifique\MissionSpecifiqueForm;
use Application\Form\MissionSpecifique\MissionSpecifiqueFormFactory;
use Application\Form\MissionSpecifique\MissionSpecifiqueHydrator;
use Application\Form\MissionSpecifique\MissionSpecifiqueHydratorFactory;
use Application\Provider\Privilege\MissionspecifiquePrivileges;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_INDEX,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_MODIFIER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_HISTORISER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'missions' => [
                                'label' => 'Gestion des missions',
                                'route' => 'mission-specifique',
                                'resource' =>  MissionspecifiquePrivileges::getResourceId(MissionspecifiquePrivileges::MISSIONSPECIFIQUE_INDEX),
                                'order'    => 1000,
                                'dropdown-header' => true,
                            ],
                            'mission-specifique' => [
                                'label' => 'Missions spécifiques',
                                'route' => 'mission-specifique',
                                'resource' =>  MissionspecifiquePrivileges::getResourceId(MissionspecifiquePrivileges::MISSIONSPECIFIQUE_INDEX),
                                'order'    => 1030,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'mission-specifique' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mission-specifique',
                    'defaults' => [
                        'controller' => MissionSpecifiqueController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:mission',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:mission',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:mission',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:mission',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:mission',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
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
            MissionSpecifiqueService::class => MissionSpecifiqueServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MissionSpecifiqueController::class => MissionSpecifiqueControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            MissionSpecifiqueForm::class => MissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            MissionSpecifiqueHydrator::class => MissionSpecifiqueHydratorFactory::class,
        ],
    ]

];