<?php

namespace Application;

use Application\Controller\MissionSpecifiqueAffectationController;
use Application\Controller\MissionSpecifiqueAffectationControllerFactory;
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
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_INDEX,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'afficher-type',
                        'afficher-theme',
                        'afficher-mission',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'ajouter-type',
                        'ajouter-theme',
                        'ajouter-mission',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_AJOUTER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'modifier-type',
                        'modifier-theme',
                        'modifier-mission',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_MODIFIER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'historiser-type',
                        'historiser-theme',
                        'historiser-mission',
                        'restaurer-type',
                        'restaurer-theme',
                        'restaurer-mission',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_HISTORISER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'detruire-type',
                        'detruire-theme',
                        'detruire-mission',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_DETRUIRE,
                    ],
                ],

                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_DETRUIRE,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueAffectationController::class,
                    'action' => [
                        'generer-lettre-type',
                    ],
                    'privileges' => [
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_AFFICHER,
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
                                'resource' =>  MissionspecifiquePrivileges::getResourceId(MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_AFFICHER),
                                'order'    => 1000,
                                'dropdown-header' => true,
                            ],
                            'mission-specifique' => [
                                'label' => 'Missions spécifiques',
                                'route' => 'mission-specifique',
                                'resource' =>  MissionspecifiquePrivileges::getResourceId(MissionspecifiquePrivileges::MISSIONSPECIFIQUE_GESTION_AFFICHER),
                                'order'    => 1030,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'affectation' => [
                                'label' => 'Affectations des missions spécifiques',
                                'route' => 'mission-specifique/affectation',
                                'resource' =>  MissionspecifiquePrivileges::getResourceId(MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_INDEX),
                                'order'    => 1040,
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
                    'mission' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/mission',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:mission',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'afficher-mission',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'ajouter-mission',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:mission',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'modifier-mission',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:mission',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'historiser-mission',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:mission',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'restaurer-mission',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:mission',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'detruire-mission',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'type' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/type',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:type',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'afficher-type',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'ajouter-type',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:type',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'modifier-type',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:type',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'historiser-type',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:type',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'restaurer-type',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:type',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'detruire-type',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'theme' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/theme',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:theme',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'afficher-theme',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'ajouter-theme',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:theme',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'modifier-theme',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:theme',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'historiser-theme',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:theme',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'restaurer-theme',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:theme',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueController::class,
                                        'action'     => 'detruire-theme',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'affectation' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/affectation',
                            'defaults' => [
                                'controller' => MissionSpecifiqueAffectationController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:agent-mission-specifique',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter[/:structure]',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:agent-mission-specifique',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:agent-mission-specifique',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:agent-mission-specifique',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:agent-mission-specifique',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'detruire',
                                    ],
                                ],
                            ],
                            'generer-lettre-type' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/generer-lettre-type/:agent-mission-specifique',
                                    'defaults' => [
                                        'controller' => MissionSpecifiqueAffectationController::class,
                                        'action'     => 'generer-lettre-type',
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
            MissionSpecifiqueService::class => MissionSpecifiqueServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MissionSpecifiqueController::class => MissionSpecifiqueControllerFactory::class,
            MissionSpecifiqueAffectationController::class => MissionSpecifiqueAffectationControllerFactory::class,
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