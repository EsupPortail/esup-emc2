<?php

namespace Application;

use Application\Assertion\AgentAssertion;
use Application\Assertion\AgentAssertionFactory;
use Application\Controller\AgentController;
use Application\Controller\AgentControllerFactory;
use Application\Form\Agent\AgentForm;
use Application\Form\Agent\AgentFormFactory;
use Application\Form\Agent\AgentHydrator;
use Application\Form\Agent\AgentHydratorFactory;
use Application\Form\AgentApplication\AgentApplicationForm;
use Application\Form\AgentApplication\AgentApplicationFormFactory;
use Application\Form\AgentApplication\AgentApplicationHydrator;
use Application\Form\AgentApplication\AgentApplicationHydratorFactory;
use Application\Form\AgentCompetence\AgentCompetenceForm;
use Application\Form\AgentCompetence\AgentCompetenceFormFactory;
use Application\Form\AgentCompetence\AgentCompetenceHydrator;
use Application\Form\AgentCompetence\AgentCompetenceHydratorFactory;
use Application\Form\AgentFormation\AgentFormationForm;
use Application\Form\AgentFormation\AgentFormationFormFactory;
use Application\Form\AgentFormation\AgentFormationHydrator;
use Application\Form\AgentFormation\AgentFormationHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceFactory;
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
                        'afficher-agent-application',
                        'afficher-agent-competence',
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
                        'modifier',
                        'ajouter-agent-mission-specifique',
                        'modifier-agent-mission-specifique',
                        'upload-fichier',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_EDITER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'ajouter-agent-application',
                        'ajouter-agent-competence',
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
                        'modifier-agent-application',
                        'modifier-agent-competence',
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
                        'historiser-agent-mission-specifique',
                        'restaurer-agent-mission-specifique',
                        'detruire-agent-mission-specifique',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_EFFACER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'historiser-agent-application',
                        'restaurer-agent-application',
                        'historiser-agent-competence',
                        'restaurer-agent-competence',
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
                        'detruire-agent-competence',
                        'detruire-agent-application',
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

                    /** Route des AgentMissionSpecifique ********************************************************************/

                    'ajouter-agent-mission-specifique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent-mission-specifique/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-agent-mission-specifique',
                            ],
                        ],
                    ],
                    'modifier-agent-mission-specifique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-agent-mission-specifique/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-agent-mission-specifique',
                            ],
                        ],
                    ],
                    'historiser-agent-mission-specifique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent-mission-specifique/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-agent-mission-specifique',
                            ],
                        ],
                    ],
                    'restaurer-agent-mission-specifique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent-mission-specifique/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-agent-mission-specifique',
                            ],
                        ],
                    ],
                    'detruire-agent-mission-specifique' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-agent-mission-specifique/:agent-mission-specifique',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-agent-mission-specifique',
                            ],
                        ],
                    ],

                    /** Route des AgentApplication ********************************************************************/

                    'ajouter-agent-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent-application/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-agent-application',
                            ],
                        ],
                    ],
                    'afficher-agent-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agent-application/:agent-application',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-agent-application',
                            ],
                        ],
                    ],
                    'modifier-agent-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-agent-application/:agent-application',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-agent-application',
                            ],
                        ],
                    ],
                    'historiser-agent-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent-application/:agent-application',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-agent-application',
                            ],
                        ],
                    ],
                    'restaurer-agent-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent-application/:agent-application',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-agent-application',
                            ],
                        ],
                    ],
                    'detruire-agent-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-agent-application/:agent-application',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-agent-application',
                            ],
                        ],
                    ],

                    /** Route des AgentCompetence *********************************************************************/

                    'ajouter-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent-competence/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-agent-competence',
                            ],
                        ],
                    ],
                    'afficher-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-agent-competence',
                            ],
                        ],
                    ],
                    'modifier-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-agent-competence',
                            ],
                        ],
                    ],
                    'historiser-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-agent-competence',
                            ],
                        ],
                    ],
                    'restaurer-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-agent-competence',
                            ],
                        ],
                    ],
                    'detruire-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-agent-competence',
                            ],
                        ],
                    ],

                    /** Route des AgentFormation **********************************************************************/

                    'ajouter-agent-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent-formation/:agent',
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

                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
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
            AgentForm::class => AgentFormFactory::class,
            AgentApplicationForm::class => AgentApplicationFormFactory::class,
            AgentCompetenceForm::class => AgentCompetenceFormFactory::class,
            AgentFormationForm::class => AgentFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentHydrator::class => AgentHydratorFactory::class,
            AgentApplicationHydrator::class => AgentApplicationHydratorFactory::class,
            AgentCompetenceHydrator::class => AgentCompetenceHydratorFactory::class,
            AgentFormationHydrator::class => AgentFormationHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agent' => AgentViewHelper::class,
            'agentStatut' => AgentStatutViewHelper::class,
            'agentGrade' => AgentGradeViewHelper::class,
        ],
    ],


];