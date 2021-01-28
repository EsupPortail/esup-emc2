<?php

namespace Application;

use Application\Assertion\AgentAssertion;
use Application\Assertion\AgentAssertionFactory;
use Application\Controller\AgentController;
use Application\Controller\AgentControllerFactory;
use Application\Form\AgentFormation\AgentFormationForm;
use Application\Form\AgentFormation\AgentFormationFormFactory;
use Application\Form\AgentFormation\AgentFormationHydrator;
use Application\Form\AgentFormation\AgentFormationHydratorFactory;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Form\SelectionAgent\SelectionAgentFormFactory;
use Application\Form\SelectionAgent\SelectionAgentHydrator;
use Application\Form\SelectionAgent\SelectionAgentHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceFactory;
use Application\View\Helper\AgentAffectationViewHelper;
use Application\View\Helper\AgentApplicationViewHelper;
use Application\View\Helper\AgentCompetenceViewHelper;
use Application\View\Helper\AgentFormationViewHelper;
use Application\View\Helper\AgentGradeViewHelper;
use Application\View\Helper\AgentStatutViewHelper;
use Application\View\Helper\AgentViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                            AgentPrivileges::AGENT_ELEMENT_VOIR,
                            AgentPrivileges::AGENT_ELEMENT_AJOUTER,
                            AgentPrivileges::AGENT_ELEMENT_MODIFIER,
                            AgentPrivileges::AGENT_ELEMENT_HISTORISER,
                            AgentPrivileges::AGENT_ELEMENT_DETRUIRE,
                            AgentPrivileges::AGENT_ELEMENT_VALIDER,
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
                        'afficher-application',
                        'afficher-competence',
                        'afficher-agent-formation',
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
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_EDITER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'ajouter-application',
                        'ajouter-competence',
                        'ajouter-agent-formation',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_AJOUTER,
                    ],
                    'assertion'  => AgentAssertion::class,
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'modifier-application',
                        'modifier-competence',
                        'modifier-agent-formation',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_MODIFIER,
                    ],
                    'assertion'  => AgentAssertion::class,
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'historiser-application',
                        'restaurer-application',
                        'historiser-competence',
                        'restaurer-competence',
                        'historiser-agent-formation',
                        'restaurer-agent-formation',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_MODIFIER,
                    ],
                    'assertion'  => AgentAssertion::class,
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'detruire-agent-formation',
                        'detruire-competence',
                        'detruire-application',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_DETRUIRE,
                    ],
                    'assertion'  => AgentAssertion::class,
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

                    'ajouter-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-application/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-application',
                            ],
                        ],
                    ],
                    'afficher-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-application/:agent/:application-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-application',
                            ],
                        ],
                    ],
                    'modifier-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-application/:agent/:application-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-application',
                            ],
                        ],
                    ],
                    'historiser-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-application/:agent/:application-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-application',
                            ],
                        ],
                    ],
                    'restaurer-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-application/:agent/:application-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-application',
                            ],
                        ],
                    ],
                    'detruire-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-application/:agent/:application-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-application',
                            ],
                        ],
                    ],

                    /** Route des AgentCompetence *********************************************************************/

                    'ajouter-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-competence/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-competence',
                            ],
                        ],
                    ],
                    'afficher-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-competence/:agent/:competence-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-competence',
                            ],
                        ],
                    ],
                    'modifier-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-competence/:agent/:competence-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-competence',
                            ],
                        ],
                    ],
                    'historiser-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-competence/:agent/:competence-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-competence',
                            ],
                        ],
                    ],
                    'restaurer-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-competence/:agent/:competence-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-competence',
                            ],
                        ],
                    ],
                    'detruire-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-competence/:agent/:competence-element',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-competence',
                            ],
                        ],
                    ],

                    /** Route des AgentFormation **********************************************************************/

                    'ajouter-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent-formation/:agent[/:formation]',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-agent-formation',
                            ],
                        ],
                    ],
                    'afficher-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agent-formation/:agent-formation',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-agent-formation',
                            ],
                        ],
                    ],
                    'modifier-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-agent-formation/:agent-formation',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-agent-formation',
                            ],
                        ],
                    ],
                    'historiser-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent-formation/:agent-formation',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-agent-formation',
                            ],
                        ],
                    ],
                    'restaurer-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent-formation/:agent-formation',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-agent-formation',
                            ],
                        ],
                    ],
                    'detruire-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-agent-formation/:agent-formation',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-agent-formation',
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
                            'route'    => '/afficher/:agent',
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
                                'resource' => AgentPrivileges::getResourceId(AgentPrivileges::AGENT_AFFICHER),
                                'order'    => 100,
                            ],
                        ],
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
    'controllers'     => [
        'factories' => [
            AgentController::class => AgentControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentFormationForm::class => AgentFormationFormFactory::class,
            SelectionAgentForm::class => SelectionAgentFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentFormationHydrator::class => AgentFormationHydratorFactory::class,
            SelectionAgentHydrator::class => SelectionAgentHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agent' => AgentViewHelper::class,
            'agentAffectation' => AgentAffectationViewHelper::class,
            'agentApplication' => AgentApplicationViewHelper::class,
            'agentCompetence' => AgentCompetenceViewHelper::class,
            'agentFormation' => AgentFormationViewHelper::class,
            'agentStatut' => AgentStatutViewHelper::class,
            'agentGrade' => AgentGradeViewHelper::class,
        ],
    ],

];