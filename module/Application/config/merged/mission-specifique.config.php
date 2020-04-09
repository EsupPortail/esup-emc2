<?php

namespace Application;

use Application\Controller\MissionSpecifiqueController;
use Application\Controller\MissionSpecifiqueControllerFactory;
use Application\Form\RessourceRh\MissionSpecifiqueForm;
use Application\Form\RessourceRh\MissionSpecifiqueFormFactory;
use Application\Form\RessourceRh\MissionSpecifiqueHydrator;
use Application\Form\RessourceRh\MissionSpecifiqueHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\RessourceRhPrivileges;
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
                        'afficher-type',
                        'afficher-theme',
                        'afficher-mission',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
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
                        RessourceRhPrivileges::AJOUTER,
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
                        RessourceRhPrivileges::MODIFIER,
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
                        RessourceRhPrivileges::MODIFIER,
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
                        RessourceRhPrivileges::EFFACER,
                    ],
                ],


                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'affectation',
                        'afficher',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionSpecifiqueController::class,
                    'action' => [
                        'ajouter',
                        'editer',
                        'historiser',
                        'restaurer',
                        'detruire',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_EDITER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'mission-specifique' => [
                                'label' => 'Missions spécifiques',
                                'route' => 'mission-specifique',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'order'    => 1100,
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
                ],
            ],
//            'agent-mission-specifique' => [
//                'type'  => Literal::class,
//                'options' => [
//                    'route'    => '/agent-mission-specifique',
//                    'defaults' => [
//                        'controller' => MissionSpecifiqueController::class,
//                        'action'     => 'affectation',
//                    ],
//                ],
//                'may_terminate' => true,
//                'child_routes' => [
//                    'ajouter' => [
//                        'type'  => Literal::class,
//                        'options' => [
//                            'route'    => '/ajouter',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'ajouter',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
//                    'afficher' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/afficher/:affectation',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'afficher',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
//                    'editer' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/editer/:affectation',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'editer',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
//                    'historiser' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/historiser/:affectation',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'historiser',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
//                    'restaurer' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/restaurer/:affectation',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'restaurer',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
//                    'detruire' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/detruire/:affectation',
//                            'defaults' => [
//                                'controller' => MissionSpecifiqueController::class,
//                                'action'     => 'detruire',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
//                ],
//            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            MissionSpecifiqueService::class => MissionSpecifiqueServiceFactory::class,
//            MissionSpecifiqueAffectationService::class => MissionSpecifiqueAffectationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MissionSpecifiqueController::class => MissionSpecifiqueControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
//            AgentMissionSpecifiqueForm::class => AgentMissionSpecifiqueFormFactory::class,

            MissionSpecifiqueForm::class => MissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
//            AgentMissionSpecifiqueHydrator::class => AgentMissionSpecifiqueHydratorFactory::class,

            MissionSpecifiqueHydrator::class => MissionSpecifiqueHydratorFactory::class,
        ],
    ]

];