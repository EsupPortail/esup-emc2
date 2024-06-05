<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\AgentForceSansObligationController;
use EntretienProfessionnel\Controller\AgentForceSansObligationControllerFactory;
use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationForm;
use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationFormFactory;
use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationHydrator;
use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\AgentforcesansobligationPrivileges;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationService;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentForceSansObligationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        AgentforcesansobligationPrivileges::AGENTFORCESANSOBLIGATION_INDEX,
                    ],
                ],
                [
                    'controller' => AgentForceSansObligationController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        AgentforcesansobligationPrivileges::AGENTFORCESANSOBLIGATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => AgentForceSansObligationController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        AgentforcesansobligationPrivileges::AGENTFORCESANSOBLIGATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => AgentForceSansObligationController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        AgentforcesansobligationPrivileges::AGENTFORCESANSOBLIGATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => AgentForceSansObligationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        AgentforcesansobligationPrivileges::AGENTFORCESANSOBLIGATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => AgentForceSansObligationController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        AgentforcesansobligationPrivileges::AGENTFORCESANSOBLIGATION_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'agent-avec-forcage' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/agent-avec-forcage',
                            'defaults' => [
                                /** @see AgentForceSansObligationController::indexAction() */
                                'controller' => AgentForceSansObligationController::class,
                                'action' => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see AgentForceSansObligationController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:agent-force-sans-obligation',
                                    'defaults' => [
                                        /** @see AgentForceSansObligationController::afficherAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:agent-force-sans-obligation',
                                    'defaults' => [
                                        /** @see AgentForceSansObligationController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:agent-force-sans-obligation',
                                    'defaults' => [
                                        /** @see AgentForceSansObligationController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:agent-force-sans-obligation',
                                    'defaults' => [
                                        /** @see AgentForceSansObligationController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:agent-force-sans-obligation',
                                    'defaults' => [
                                        /** @see AgentForceSansObligationController::supprimerAction() */
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                        ],
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
                            'agent-force' => [
                                'label' => "Agent·es avec forçage d'entretien professionnel",
                                'route' => 'entretien-professionnel/agent-avec-forcage',
                                'resource' => PrivilegeController::getResourceId(AgentForceSansObligationController::class, 'index'),
                                'order' => 3100,
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
            AgentForceSansObligationService::class => AgentForceSansObligationServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            AgentForceSansObligationController::class => AgentForceSansObligationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentForceSansObligationForm::class => AgentForceSansObligationFormFactory::class
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentForceSansObligationHydrator::class => AgentForceSansObligationHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [],
    ],


];