<?php

namespace Structure;

use Structure\Assertion\StructureAssertion;
use Structure\Assertion\StructureAssertionFactory;
use Structure\Controller\StructureController;
use Structure\Controller\StructureControllerFactory;
use Structure\Event\InfoStructure\InfoStructureEvent;
use Structure\Event\InfoStructure\InfoStructureEventFactory;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Service\Notification\NotificationService;
use Structure\Service\Notification\NotificationServiceFactory;
use Structure\Service\Structure\StructureService;
use Structure\Service\Structure\StructureServiceFactory;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Structure' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            StructurePrivileges::STRUCTURE_AFFICHER,
                            StructurePrivileges::STRUCTURE_DESCRIPTION,
                            StructurePrivileges::STRUCTURE_GESTIONNAIRE,
                            StructurePrivileges::STRUCTURE_COMPLEMENT_AGENT,
                            StructurePrivileges::STRUCTURE_AGENT_FORCE,
                        ],
                        'resources' => ['Structure'],
                        'assertion' => StructureAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'index',
                        'extraction-listing-fiche-poste',
                        'extraction-listing-mission-specifique',
                        'organigramme',
                        'organigramme-pdf',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'ajouter-fiche-poste-recrutement',
                        'dupliquer-fiche-poste-recrutement',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_DESCRIPTION,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'rechercher',
                        'rechercher-with-structure-mere',
                        'graphe',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'description',
                        'agents',
                        'missions-specifiques',
                        'fiches-de-poste',
                        'extractions',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                    'assertion'  => StructureAssertion::class,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'editer-description',
                        'toggle-resume-mere',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_DESCRIPTION,
                    'assertion'  => StructureAssertion::class,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'ajouter-manuellement-agent',
                        'retirer-manuellement-agent',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AGENT_FORCE,
                    'assertion'  => StructureAssertion::class,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'synchroniser'
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_INDEX,
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'suivis' => [
                        'pages' => [
                            'structure' => [
                                'label' => 'Structures',
                                'route' => 'structure',
                                'resource' =>  StructurePrivileges::getResourceId(StructurePrivileges::STRUCTURE_INDEX) ,
                                'order'    => 110,
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
            'structure' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/structure',
                    'defaults' => [
                        'controller' => StructureController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'synchroniser' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/synchroniser',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'synchroniser',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/description/:structure',
                            'defaults' => [
                                /** @see StructureController::descriptionAction() */
                                'controller' => StructureController::class,
                                'action'     => 'description',
                            ],
                        ],
                    ],
                    'agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/agents/:structure',
                            'defaults' => [
                                /** @see StructureController::agentsAction() */
                                'controller' => StructureController::class,
                                'action'     => 'agents',
                            ],
                        ],
                    ],
                    'missions-specifiques' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/missions-specifiques/:structure',
                            'defaults' => [
                                /** @see StructureController::missionsSpecifiquesAction() */
                                'controller' => StructureController::class,
                                'action'     => 'missions-specifiques',
                            ],
                        ],
                    ],
                    'fiches-de-poste' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/fiches-de-poste/:structure',
                            'defaults' => [
                                /** @see StructureController::fichesDePosteAction() */
                                'controller' => StructureController::class,
                                'action'     => 'fiches-de-poste',
                            ],
                        ],
                    ],
                    'extractions' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/extractions/:structure',
                            'defaults' => [
                                /** @see StructureController::extractionsAction() */
                                'controller' => StructureController::class,
                                'action'     => 'extractions',
                            ],
                        ],
                    ],
                    'organigramme' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/organigramme/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'organigramme',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'organigramme-pdf' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/organigramme-pdf/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'organigramme-pdf',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'ajouter-fiche-poste-recrutement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-fiche-poste-recrutement/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-fiche-poste-recrutement',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'dupliquer-fiche-poste-recrutement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dupliquer-fiche-poste-recrutement/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'dupliquer-fiche-poste-recrutement',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'graphe' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/graphe[/:structure]',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'graphe',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'ajouter-manuellement-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-manuellement-agent/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-manuellement-agent',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'retirer-manuellement-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-manuellement-agent/:structure/:agent',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'retirer-manuellement-agent',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'editer-description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer-description/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'editer-description',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'toggle-resume-mere' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-resume-mere/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'toggle-resume-mere',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    /** Fonctions de recherche de structures **********************************************************/
                    'rechercher' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'rechercher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'rechercher-with-structure-mere' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/rechercher-with-structure-mere/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'rechercher-with-structure-mere',
                            ],
                        ],
                        'may_terminate' => true,
                    ],

                    /** Extraction  */
                    'extraction' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/extraction',
                            'defaults' => [
                                'controller' => StructureController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'listing-fiche-poste' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/listing-fiche-poste/:structure',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'extraction-listing-fiche-poste',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [],
                            ],
                            'listing-mission-specifique' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/listing-mission-specifique/:structure',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'extraction-listing-mission-specifique',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            StructureService::class => StructureServiceFactory::class,
            StructureAgentForceService::class => StructureAgentForceServiceFactory::class,

            StructureAssertion::class => StructureAssertionFactory::class,
            NotificationService::class => NotificationServiceFactory::class,
            InfoStructureEvent::class => InfoStructureEventFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            StructureController::class => StructureControllerFactory::class,
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