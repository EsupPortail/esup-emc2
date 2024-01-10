<?php

namespace Application;

use Application\Controller\MissionSpecifiqueAffectationController;
use Application\Controller\MissionSpecifiqueAffectationControllerFactory;
use Application\Provider\Privilege\MissionspecifiqueaffectationPrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_INDEX,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                    ],
                    'privileges' => [
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_DETRUIRE,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'generer-lettre-type',
                    ],
                    'privileges' => [
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_AFFICHER,
                    ],
                ],

            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'affectation' => [
                                'label' => 'Affectations des missions spécifiques',
                                'route' => 'mission-specifique-affectation',
                                'resource' => MissionspecifiqueaffectationPrivileges::getResourceId(MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_INDEX),
                                'order' => 1040,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'mission-specifique-affectation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mission-specifique-affectation',
                    'defaults' => [
                        'controller' => MissionSpecifiqueAffectationController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ajouter[/:structure]',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/detruire/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'detruire',
                            ],
                        ],
                    ],
                    'generer-lettre-type' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/generer-lettre-type/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action' => 'generer-lettre-type',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers' => [
        'factories' => [
            MissionSpecifiqueAffectationController::class => MissionSpecifiqueAffectationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ]

];