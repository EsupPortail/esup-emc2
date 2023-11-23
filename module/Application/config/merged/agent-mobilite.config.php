<?php

namespace Application;

use Application\Controller\AgentMobiliteController;
use Application\Controller\AgentMobiliteControllerFactory;
use Application\Form\AgentMobilite\AgentMobiliteForm;
use Application\Form\AgentMobilite\AgentMobiliteFormFactory;
use Application\Form\AgentMobilite\AgentMobiliteHydrator;
use Application\Form\AgentMobilite\AgentMobiliteHydratorFactory;
use Application\Provider\Privilege\AgentmobilitePrivileges;
use Application\Service\AgentMobilite\AgentMobiliteService;
use Application\Service\AgentMobilite\AgentMobiliteServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentMobiliteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        AgentMobilitePrivileges::AGENTMOBILITE_INDEX
                    ],
                ],
                [
                    'controller' => AgentMobiliteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        AgentMobilitePrivileges::AGENTMOBILITE_AJOUTER
                    ],
                ],
                [
                    'controller' => AgentMobiliteController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        AgentMobilitePrivileges::AGENTMOBILITE_MODIFIER
                    ],
                ],
                [
                    'controller' => AgentMobiliteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        AgentMobilitePrivileges::AGENTMOBILITE_HISTORISER
                    ],
                ],
                [
                    'controller' => AgentMobiliteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        AgentMobilitePrivileges::AGENTMOBILITE_SUPPRIMER
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'agent' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/agent',
                ],
                'child_routes' => [
                    'mobilite' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/mobilite',
                            'defaults' => [
                                'controller' => AgentMobiliteController::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:agent]',
                                    'defaults' => [
                                        /** @see AgentMobiliteController::ajouterAction() */
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:agent-mobilite',
                                    'defaults' => [
                                        /** @see AgentMobiliteController::modifierAction() */
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:agent-mobilite',
                                    'defaults' => [
                                        /** @see AgentMobiliteController::historiserAction() */
                                        'action' => 'historiser'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:agent-mobilite',
                                    'defaults' => [
                                        /** @see AgentMobiliteController::restaurerAction() */
                                        'action' => 'restaurer'
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:agent-mobilite',
                                    'defaults' => [
                                        /** @see AgentMobiliteController::supprimerAction() */
                                        'action' => 'supprimer'
                                    ],
                                ],
                            ],
                        ],
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
                            'agent-mobilite' => [
                                'label'    => 'Gestion des statuts de mobilités des agents',
                                'route'    => 'agent/mobilite',
                                'resource' => PrivilegeController::getResourceId(AgentMobiliteController::class, 'index') ,
                                'order'    => 100000,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentMobiliteService::class => AgentMobiliteServiceFactory::class
        ],
    ],
    'controllers' => [
        'factories' => [
            AgentMobiliteController::class => AgentMobiliteControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentMobiliteForm::class => AgentMobiliteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentMobiliteHydrator::class => AgentMobiliteHydratorFactory::class,
        ],
    ]

];