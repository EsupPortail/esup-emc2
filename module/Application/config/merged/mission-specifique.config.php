<?php

namespace Application;

use Application\Controller\MissionSpecifiqueController;
use Application\Controller\MissionSpecifiqueControllerFactory;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormFactory;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueHydrator;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
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
                        'affectation',
                        'afficher',
                    ],
                    'privileges' => [
                        AgentPrivileges::AFFICHER,
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
                        AgentPrivileges::EDITER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'agent-mission-specifique' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/agent-mission-specifique',
                    'defaults' => [
                        'controller' => MissionSpecifiqueController::class,
                        'action'     => 'affectation',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:affectation',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:affectation',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'editer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:affectation',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:affectation',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:affectation',
                            'defaults' => [
                                'controller' => MissionSpecifiqueController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                        'may_terminate' => true,
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
            AgentMissionSpecifiqueForm::class => AgentMissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentMissionSpecifiqueHydrator::class => AgentMissionSpecifiqueHydratorFactory::class,
        ],
    ]

];