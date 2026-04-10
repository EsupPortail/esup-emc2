<?php

namespace Agent;

use Agent\Assertion\AgentAssertion;
use Agent\Assertion\AgentAssertionFactory;
use Agent\Controller\AgentController;
use Agent\Controller\AgentControllerFactory;
use Agent\Form\SelectionAgent\SelectionAgentForm;
use Agent\Form\SelectionAgent\SelectionAgentFormFactory;
use Agent\Form\SelectionAgent\SelectionAgentHydrator;
use Agent\Form\SelectionAgent\SelectionAgentHydratorFactory;
use Agent\Provider\Privilege\AgentPrivileges;
use Agent\Service\Agent\AgentService;
use Agent\Service\Agent\AgentServiceFactory;
use Agent\View\Helper\AgentOngletViewHelper;
use Agent\View\Helper\AgentOngletViewHelperFactory;
use Agent\View\Helper\AgentViewHelperFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Agent' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            AgentPrivileges::AGENT_AFFICHER,
//                            AgentPrivileges::AGENT_ELEMENT_VOIR,
//                            AgentPrivileges::AGENT_ELEMENT_AJOUTER,
//                            AgentPrivileges::AGENT_ELEMENT_MODIFIER,
//                            AgentPrivileges::AGENT_ELEMENT_HISTORISER,
//                            AgentPrivileges::AGENT_ELEMENT_DETRUIRE,
//                            AgentPrivileges::AGENT_ELEMENT_VALIDER,
//                            AgentPrivileges::AGENT_ACQUIS_AFFICHER,
//                            AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                        ],
                        'resources' => ['Agent'],
                        'assertion' => AgentAssertion::class
                    ],
//                    [
//                        'privileges' => [
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_SUPERIEUR,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_AUTORITE,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_COMPTE,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_CARRIERECOMPLETE,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_DATERESUME,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_AFFECTATION,
//                            AgentaffichagePrivileges::AGENTAFFICHAGE_TEMOIN_STATUT,
//                        ],
//                        'resources' => ['Agent'],
//                        'assertion' => AgentAffichageAssertion::class
//                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_INDEX,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'acquis',
                        'informations',
                        'missions-specifiques',
                        'portfolio',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
                    ],
                    'assertion' => AgentAssertion::class,
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'afficher-statuts-grades',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
//                        AgentaffichagePrivileges::AGENTAFFICHAGE_CARRIERECOMPLETE,
                    ],
//                    'assertion' => AgentAffichageAssertion::class,
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'rechercher',
                        'rechercher-large',
                        'rechercher-responsable',
                        'rechercher-gestionnaire',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_RECHERCHER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'rechercher-with-structure-mere',
                    ],
                    'roles' => [],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'upload-fichier',
                        'upload-fiche-poste-pdf',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_EDITER,
                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                        AgentPrivileges::AGENT_ELEMENT_AJOUTER,
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
                    'defaults' => [
                        /** @see AgentController::indexAction() */
                        'controller' => AgentController::class,
                        'action' => 'index'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'acquis' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/acquis/:agent',
                            'defaults' => [
                                /** @see AgentController::acquisAction() */
                                'controller' => AgentController::class,
                                'action' => 'acquis'
                            ],
                        ],
                    ],
                    'afficher-statuts-grades' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher-statuts-grades/:agent',
                            'defaults' => [
                                /** @see AgentController::afficherStatutsGradesAction() */
                                'controller' => AgentController::class,
                                'action' => 'afficher-statuts-grades',
                            ],
                        ],
                    ],
                    'informations' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/informations[/:agent]',
                            'defaults' => [
                                /** @see AgentController::informationsAction() */
                                'controller' => AgentController::class,
                                'action' => 'informations'
                            ],
                        ],
                    ],
                    'missions-specifiques' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/missions-specifiques/:agent',
                            'defaults' => [
                                /** @see AgentController::missionsSpecifiquesAction() */
                                'controller' => AgentController::class,
                                'action' => 'missions-specifiques'
                            ],
                        ],
                    ],
                    'portfolio' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/portfolio/:agent',
                            'defaults' => [
                                /** @see AgentController::portfolioAction() */
                                'controller' => AgentController::class,
                                'action' => 'portfolio'
                            ],
                        ],
                    ],
                    /** AUTRE  ****************************************************************************************/

                    'upload-fichier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/upload-fichier/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action' => 'upload-fichier',
                            ],
                        ],
                    ],
                    'upload-fiche-poste-pdf' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/upload-fiche-poste-pdf/:agent',
                            'defaults' => [
                                /** @see AgentController::uploadFichePostePdfAction() */
                                'controller' => AgentController::class,
                                'action' => 'upload-fiche-poste-pdf',
                            ],
                        ],
                    ],
                    /** Fonctions de recherche ************************************************************************/
                    'rechercher' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/rechercher',
                            'defaults' => [
                                /** @see AgentController::rechercherAction() */
                                'controller' => AgentController::class,
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'rechercher-large' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/rechercher-large',
                            'defaults' => [
                                /** @see AgentController::rechercherLargeAction() */
                                'controller' => AgentController::class,
                                'action' => 'rechercher-large',
                            ],
                        ],
                    ],
                    'rechercher-with-structure-mere' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/rechercher-with-structure-mere/:structure',
                            'defaults' => [
                                /** @see AgentController::rechercherWithStructureMereAction() */
                                'action' => 'rechercher-with-structure-mere',
                            ],
                        ],
                    ],
                    'rechercher-responsable' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/rechercher-responsable',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action' => 'rechercher-responsable',
                            ],
                        ],
                    ],
                    'rechercher-gestionnaire' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/rechercher-gestionnaire',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action' => 'rechercher-gestionnaire',
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
                    'suivis' => [
                        'pages' => [
                            'agent' => [
                                'label' => 'Agents',
                                'route' => 'agent',
                                'resource' => PrivilegeController::getResourceId(AgentController::class, 'index'),
                                'order' => 10,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                    'donnees' => [
                        'order' => 0100,
                        'label' => 'Données personnelles',
                        'title' => "Gestion des données d'un agent",
                        /** @see AgentController::informationsAction() */
                        'route' => 'agent/informations',
                        'resource' => AgentPrivileges::getResourceId(AgentPrivileges::AGENT_AFFICHER_DONNEES),
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentAssertion::class => AgentAssertionFactory::class,
            AgentService::class => AgentServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            AgentController::class => AgentControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SelectionAgentForm::class => SelectionAgentFormFactory::class,

        ],
    ],
    'hydrators' => [
        'factories' => [
            SelectionAgentHydrator::class => SelectionAgentHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'agent' => AgentViewHelperFactory::class,
            AgentOngletViewHelper::class => AgentOngletViewHelperFactory::class,
        ],
        'aliases' => [
            'agentOnglet' => AgentOngletViewHelper::class,
        ],
    ],
];