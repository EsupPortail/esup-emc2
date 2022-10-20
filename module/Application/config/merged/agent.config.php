<?php

namespace Application;

use Application\Assertion\AgentAssertion;
use Application\Assertion\AgentAssertionFactory;
use Application\Controller\AgentController;
use Application\Controller\AgentControllerFactory;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormFactory;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueHydrator;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueHydratorFactory;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Form\SelectionAgent\SelectionAgentFormFactory;
use Application\Form\SelectionAgent\SelectionAgentHydrator;
use Application\Form\SelectionAgent\SelectionAgentHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceFactory;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAffectation\AgentAffectationServiceFactory;
use Application\Service\AgentGrade\AgentGradeService;
use Application\Service\AgentGrade\AgentGradeServiceFactory;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceFactory;
use Application\Service\AgentQuotite\AgentQuotiteService;
use Application\Service\AgentQuotite\AgentQuotiteServiceFactory;
use Application\Service\AgentStatut\AgentStatutService;
use Application\Service\AgentStatut\AgentStatutServiceFactory;
use Application\View\Helper\AgentAffectationViewHelper;
use Application\View\Helper\AgentGradeViewHelper;
use Application\View\Helper\AgentStatutViewHelper;
use Application\View\Helper\AgentViewHelper;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
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
                            AgentPrivileges::AGENT_ELEMENT_VOIR,
                            AgentPrivileges::AGENT_ELEMENT_AJOUTER,
                            AgentPrivileges::AGENT_ELEMENT_MODIFIER,
                            AgentPrivileges::AGENT_ELEMENT_HISTORISER,
                            AgentPrivileges::AGENT_ELEMENT_DETRUIRE,
                            AgentPrivileges::AGENT_ELEMENT_VALIDER,
                            AgentPrivileges::AGENT_ACQUIS_AFFICHER,
                            AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                        ],
                        'resources' => ['Agent'],
                        'assertion' => AgentAssertion::class
                    ],
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
                        'rechercher',
                        'rechercher-large',
                        'rechercher-with-structure-mere',
                        'rechercher-responsable',
                        'rechercher-gestionnaire',

                        'afficher',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'afficher-statuts-grades',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_VOIR,
                    ],
                    'assertion'  => AgentAssertion::class,
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'upload-fichier',
                        'ajouter-formation',
                        'ajouter-application',
                        'modifier-formation',
                        'upload-fiche-poste-pdf',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_EDITER,
                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'valider-element',
                        'revoquer-element',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_VALIDER,
                    ],
                    'assertion'  => AgentAssertion::class,
                ],
                /** NEW STUFFS CCC */

                [
                    'controller' => AgentController::class,
                    'action' => [
                        'ajouter-accompagnement',
                        'modifier-accompagnement',
                        'historiser-accompagnement',
                        'restaurer-accompagnement',
                        'detruire-accompagnement',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_GESTION_CCC,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'agent' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/agent',
                    'defaults' => [
                        'controller' => AgentController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    /** Fonctions de recherche ************************************************************************/
                    'rechercher' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher',
                            ],
                        ],
                    ],
                    'rechercher-large' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-large',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-large',
                            ],
                        ],
                    ],
                    'rechercher-with-structure-mere' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/rechercher-with-structure-mere/:structure',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-with-structure-mere',
                            ],
                        ],
                    ],
                    'rechercher-responsable' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-responsable',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-responsable',
                            ],
                        ],
                    ],
                    'rechercher-gestionnaire' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-gestionnaire',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-gestionnaire',
                            ],
                        ],
                    ],

                    /** Routes de gestion des applications*************************************************************/

                    //TODO changer dans parcours-applicatifs
                    'ajouter-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-application/:agent[/:application]',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-application',
                            ],
                        ],
                    ],
                    //TODO changer dans parcours-de-formation
                    //TODO changer dans formation-bloc

                    'ajouter-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-formation/:agent[/:formation]',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-formation',
                            ],
                        ],
                    ],
                    //TODO changer dans formation-bloc
                    'modifier-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formation/:agent/:formation-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-formation',
                            ],
                        ],
                    ],

                    /** VALIDATION D'ELEMENT **************************************************************************/

                    'valider-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/valider-element/:type/:id',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'valider-element',
                            ],
                        ],
                    ],
                    'revoquer-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/revoquer-element/:validation',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'revoquer-element',
                            ],
                        ],
                    ],

                    /** AUTRE  ****************************************************************************************/

                    'upload-fichier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/upload-fichier/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'upload-fichier',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher[/:agent]',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'afficher-statuts-grades' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-statuts-grades/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-statuts-grades',
                            ],
                        ],
                    ],
                    'upload-fiche-poste-pdf' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/upload-fiche-poste-pdf/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'upload-fiche-poste-pdf',
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
                    'ressource' => [
                        'pages' => [
                            'agent' => [
                                'label'    => 'Agents',
                                'route'    => 'agent',
                                'resource' => PrivilegeController::getResourceId(AgentController::class, 'index') ,
                                'order'    => 10,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                    'donnees' => [
                        'order' => 0100,
                        'label' => 'Données personnelles',
                        'title' => "Gestion des données d'un agent",
                        'route' => 'agent/afficher',
                        'resource' => AgentPrivileges::getResourceId(AgentPrivileges::AGENT_AFFICHER_DONNEES),
                    ],
                    'entretien' => [
                        'order' => 0101,
                        'label' => 'Entretiens professionnels',
                        'title' => "Gestion des données d'un agent",
                        'route' => 'entretien-professionnel/index-agent',
                        'resource' => AgentPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_MESENTRETIENS),
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentAssertion::class => AgentAssertionFactory::class,

            AgentService::class => AgentServiceFactory::class,
            AgentAffectationService::class => AgentAffectationServiceFactory::class,
            AgentGradeService::class => AgentGradeServiceFactory::class,
            AgentMissionSpecifiqueService::class => AgentMissionSpecifiqueServiceFactory::class,
            AgentQuotiteService::class => AgentQuotiteServiceFactory::class,
            AgentStatutService::class => AgentStatutServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentController::class => AgentControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SelectionAgentForm::class => SelectionAgentFormFactory::class,
            AgentMissionSpecifiqueForm::class => AgentMissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SelectionAgentHydrator::class => SelectionAgentHydratorFactory::class,
            AgentMissionSpecifiqueHydrator::class => AgentMissionSpecifiqueHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agent' => AgentViewHelper::class,
            'agentAffectation' => AgentAffectationViewHelper::class,
            'agentStatut' => AgentStatutViewHelper::class,
            'agentGrade' => AgentGradeViewHelper::class,
        ],
    ],

];