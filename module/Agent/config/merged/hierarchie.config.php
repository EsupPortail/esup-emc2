<?php

namespace Application;

use Agent\Controller\AgentHierarchieController;
use Agent\Controller\AgentHierarchieControllerFactory;
use Agent\Form\AgentHierarchieCalcul\AgentHierarchieCalculForm;
use Agent\Form\AgentHierarchieCalcul\AgentHierarchieCalculFormFactory;
use Agent\Form\AgentHierarchieCalcul\AgentHierarchieCalculHydrator;
use Agent\Form\AgentHierarchieCalcul\AgentHierarchieCalculHydratorFactory;
use Agent\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Agent\Form\AgentHierarchieImportation\AgentHierarchieImportationFormFactory;
use Agent\Form\AgentHierarchieImportation\AgentHierarchieImportationHydrator;
use Agent\Form\AgentHierarchieImportation\AgentHierarchieImportationHydratorFactory;
use Agent\Form\Chaine\ChaineForm;
use Agent\Form\Chaine\ChaineFormFactory;
use Agent\Form\Chaine\ChaineHydrator;
use Agent\Form\Chaine\ChaineHydratorFactory;
use Agent\Provider\Privilege\AgentPrivileges;
use Agent\Provider\Privilege\ChainePrivileges;
use Agent\Service\AgentAutorite\AgentAutoriteService;
use Agent\Service\AgentAutorite\AgentAutoriteServiceFactory;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceFactory;
use Agent\View\Helper\AgentAutoriteViewHelper;
use Agent\View\Helper\AgentSuperieurViewHelper;
use Agent\Assertion\ChaineAssertion;
use Agent\Assertion\ChaineAssertionFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Chaine' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            ChainePrivileges::CHAINE_AFFICHER,
                            ChainePrivileges::CHAINE_AFFICHER_HISTORIQUE,
                            ChainePrivileges::CHAINE_SYNCHRONISER,
                            ChainePrivileges::CHAINE_GERER,
                        ],
                        'resources' => ['Chaine'],
                        'assertion' => ChaineAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'index',
                        'autorite',
                        'superieur',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_INDEX
                    ],
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'afficher',
                        'afficher-chaine'
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_AFFICHER,
                    ],
                    'assertion' => ChaineAssertion::class,
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'supprimer',
                        'exporter-chaines',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_GERER
                    ],
                    'assertion' => ChaineAssertion::class,
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'visualiser',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_AFFICHER_HISTORIQUE,
                    ],
                    'assertion' => ChaineAssertion::class,
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_SYNCHRONISER
                    ],
                    'assertion' => ChaineAssertion::class,
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'importer',
                        'calculer',
                        'chaine-hierarchique-json',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_IMPORTER,
                    ],
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'rechercher-agent-with-autorite',
                        'rechercher-agent-with-superieur',
                    ],
                    'roles' => [],
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'mes-agents',
                        'mes-missions-specifiques',
                        'mes-fiches-postes',
                        'mes-entretiens-professionnels',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
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
                            'autre' => [
                                'label' => 'Autres gestions',
                                'route' => 'agent/hierarchie',
                                'resource' => PrivilegeController::getResourceId(AgentHierarchieController::class, 'index'),
                                'order' => 9900,
                                'dropdown-header' => true,
                            ],
                            'hierarchie' => [
                                'label' => 'Gestion des chaînes hiérarchiques',
                                'route' => 'agent/hierarchie',
                                'resource' => PrivilegeController::getResourceId(AgentHierarchieController::class, 'index'),
                                'order' => 9999,
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
            'mes-entretiens-professionnels' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/mes-entretiens-professionnels[/:campagne]',
                    'defaults' => [
                        /** @see AgentHierarchieController::mesEntretiensProfessionnelsAction() */
                        'controller' => AgentHierarchieController::class,
                        'action' => 'mes-entretiens-professionnels',
                    ],
                ],
            ],
            'mes-agents' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mes-agents',
                    'defaults' => [
                        /** @see AgentHierarchieController::mesAgentsAction() */
                        'controller' => AgentHierarchieController::class,
                        'action' => 'mes-agents',
                    ],
                ],
            ],
            'mes-missions-specifiques' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mes-missions-specifiques',
                    'defaults' => [
                        /** @see AgentHierarchieController::mesMissionsSpecifiquesAction() */
                        'controller' => AgentHierarchieController::class,
                        'action' => 'mes-missions-specifiques',
                    ],
                ],
            ],
            'mes-fiches-postes' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mes-fiches-postes',
                    'defaults' => [
                        /** @see AgentHierarchieController::mesFichesPostesAction() */
                        'controller' => AgentHierarchieController::class,
                        'action' => 'mes-fiches-postes',
                    ],
                ],
            ],
            'agent' => [
                'child_routes' => [
                    'hierarchie' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/hierarchie',
                            'defaults' => [
                                /** @see AgentHierarchieController::indexAction() */
                                'controller' => AgentHierarchieController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter/:type[/:agent]',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::ajouterAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'autorite' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/autorite',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::autoriteAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'autorite',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::modifierAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::historiserAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::restaurerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'superieur' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/superieur',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::superieurAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'superieur',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::supprimerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],

                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:agent',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::afficherAction(): */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'afficher-chaine' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher-chaine/:type/:agent',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::afficherChaineAction(): */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'afficher-chaine',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'chaine-hierarchique-json' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/chaine-hierarchique-json[/:agent]',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::chaineHierarchiqueJsonAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'chaine-hierarchique-json',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'visualiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/visualiser/:agent/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::visualiserAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'visualiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'exporter-chaines' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/exporter-chaines/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::exporterChainesAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'exporter-chaines',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'importer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/importer/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::importerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'importer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'calculer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/calculer/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::calculerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'calculer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'rechercher-agent-with-autorite' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher-agent-with-autorite',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::rechercherAgentWithAutoriteAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'rechercher-agent-with-autorite',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'rechercher-agent-with-superieur' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher-agent-with-superieur',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::rechercherAgentWithSuperieurAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action' => 'rechercher-agent-with-superieur',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentAutoriteService::class => AgentAutoriteServiceFactory::class,
            AgentSuperieurService::class => AgentSuperieurServiceFactory::class,
            ChaineAssertion::class => ChaineAssertionFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            AgentHierarchieController::class => AgentHierarchieControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentHierarchieCalculForm::class => AgentHierarchieCalculFormFactory::class,
            AgentHierarchieImportationForm::class => AgentHierarchieImportationFormFactory::class,
            ChaineForm::class => ChaineFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentHierarchieCalculHydrator::class => AgentHierarchieCalculHydratorFactory::class,
            AgentHierarchieImportationHydrator::class => AgentHierarchieImportationHydratorFactory::class,
            ChaineHydrator::class => ChaineHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agentAutorite' => AgentAutoriteViewHelper::class,
            'agentSuperieur' => AgentSuperieurViewHelper::class,
        ],
    ]

];