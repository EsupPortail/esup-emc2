<?php

namespace Application;

use Application\Assertion\ChaineAssertion;
use Application\Assertion\ChaineAssertionFactory;
use Application\Controller\AgentHierarchieController;
use Application\Controller\AgentHierarchieControllerFactory;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculForm;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculFormFactory;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculHydrator;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculHydratorFactory;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationFormFactory;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationHydrator;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationHydratorFactory;
use Application\Form\Chaine\ChaineForm;
use Application\Form\Chaine\ChaineFormFactory;
use Application\Form\Chaine\ChaineHydrator;
use Application\Form\Chaine\ChaineHydratorFactory;
use Application\Provider\Privilege\ChainePrivileges;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentAutorite\AgentAutoriteServiceFactory;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\AgentSuperieur\AgentSuperieurServiceFactory;
use Application\View\Helper\AgentAutoriteViewHelper;
use Application\View\Helper\AgentSuperieurViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
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
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_INDEX
                    ],
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_AFFICHER,
                    ],
                    'assertion'  => ChaineAssertion::class,
                ],
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'supprimer',
                    ],
                    'privileges' => [
                        ChainePrivileges::CHAINE_GERER
                    ],
                    'assertion'  => ChaineAssertion::class,
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
                    'assertion'  => ChaineAssertion::class,
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
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'autre' => [
                                'label' => 'Autres gestions',
                                'route' => 'agent/hierarchie',
                                'resource' =>  PrivilegeController::getResourceId(AgentHierarchieController::class, 'index'),
                                'order'    => 9900,
                                'dropdown-header' => true,
                            ],
                            'hierarchie' => [
                                'label' => 'Gestion des chaînes hiérarchiques',
                                'route' => 'agent/hierarchie',
                                'resource' =>  PrivilegeController::getResourceId(AgentHierarchieController::class, 'index'),
                                'order'    => 9999,
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
            'agent' => [
                'child_routes' => [
                    'hierarchie' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/hierarchie',
                            'defaults' => [
                                /** @see AgentHierarchieController::indexAction() */
                                'controller' => AgentHierarchieController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::ajouterAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::modifierAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::historiserAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::restaurerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:chaine/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::supprimerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],

                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:agent',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::afficherAction(): */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'chaine-hierarchique-json' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/chaine-hierarchique-json[/:agent]',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::chaineHierarchiqueJsonAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'chaine-hierarchique-json',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'importer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/importer/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::importerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'importer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'calculer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/calculer/:type',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::calculerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'calculer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'rechercher-agent-with-autorite' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/rechercher-agent-with-autorite',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::rechercherAgentWithAutoriteAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'rechercher-agent-with-autorite',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'rechercher-agent-with-superieur' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/rechercher-agent-with-superieur',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::rechercherAgentWithSuperieurAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'rechercher-agent-with-superieur',
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
    'controllers'     => [
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