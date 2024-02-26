<?php

namespace MissionSpecifique;

use MissionSpecifique\Controller\MissionSpecifiqueController;
use MissionSpecifique\Controller\MissionSpecifiqueControllerFactory;
use MissionSpecifique\Form\MissionSpecifique\MissionSpecifiqueForm;
use MissionSpecifique\Form\MissionSpecifique\MissionSpecifiqueFormFactory;
use MissionSpecifique\Form\MissionSpecifique\MissionSpecifiqueHydrator;
use MissionSpecifique\Form\MissionSpecifique\MissionSpecifiqueHydratorFactory;
use MissionSpecifique\Provider\Privilege\MissionspecifiquePrivileges;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueServiceFactory;
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
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AJOUTER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'modifier',
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
                        /** @see MissionSpecifiqueController::indexAction() */
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
                                /** @see MissionSpecifiqueController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see MissionSpecifiqueController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:mission',
                            'defaults' => [
                                /** @see MissionSpecifiqueController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:mission',
                            'defaults' => [
                                /** @see MissionSpecifiqueController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:mission',
                            'defaults' => [
                                /** @see MissionSpecifiqueController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:mission',
                            'defaults' => [
                                /** @see MissionSpecifiqueController::detruireAction() */
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