<?php

namespace Application;

use Application\Assertion\AgentAssertion;
use Application\Assertion\AgentAssertionFactory;
use Application\Controller\AgentController;
use Application\Controller\AgentControllerFactory;
use Application\Form\AgentAccompagnement\AgentAccompagnementForm;
use Application\Form\AgentAccompagnement\AgentAccompagnementFormFactory;
use Application\Form\AgentAccompagnement\AgentAccompagnementHydrator;
use Application\Form\AgentAccompagnement\AgentAccompagnementHydratorFactory;
use Application\Form\AgentPPP\AgentPPPForm;
use Application\Form\AgentPPP\AgentPPPFormFactory;
use Application\Form\AgentPPP\AgentPPPHydrator;
use Application\Form\AgentPPP\AgentPPPHydratorFactory;
use Application\Form\AgentStageObservation\AgentStageObservationForm;
use Application\Form\AgentStageObservation\AgentStageObservationFormFactory;
use Application\Form\AgentStageObservation\AgentStageObservationHydrator;
use Application\Form\AgentStageObservation\AgentStageObservationHydratorFactory;
use Application\Form\AgentTutorat\AgentTutoratForm;
use Application\Form\AgentTutorat\AgentTutoratFormFactory;
use Application\Form\AgentTutorat\AgentTutoratHydrator;
use Application\Form\AgentTutorat\AgentTutoratHydratorFactory;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Form\SelectionAgent\SelectionAgentFormFactory;
use Application\Form\SelectionAgent\SelectionAgentHydrator;
use Application\Form\SelectionAgent\SelectionAgentHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceFactory;
use Application\Service\AgentAccompagnement\AgentAccompagnementService;
use Application\Service\AgentAccompagnement\AgentAccompagnementServiceFactory;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAffectation\AgentAffectationServiceFactory;
use Application\Service\AgentGrade\AgentGradeService;
use Application\Service\AgentGrade\AgentGradeServiceFactory;
use Application\Service\AgentPPP\AgentPPPService;
use Application\Service\AgentPPP\AgentPPPServiceFactory;
use Application\Service\AgentStageObservation\AgentStageObservationService;
use Application\Service\AgentStageObservation\AgentStageObservationServiceFactory;
use Application\Service\AgentStatut\AgentStatutService;
use Application\Service\AgentStatut\AgentStatutServiceFactory;
use Application\Service\AgentTutorat\AgentTutoratService;
use Application\Service\AgentTutorat\AgentTutoratServiceFactory;
use Application\View\Helper\AgentAffectationViewHelper;
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
                        'ajouter-ppp',
                        'modifier-ppp',
                        'historiser-ppp',
                        'restaurer-ppp',
                        'detruire-ppp',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_GESTION_CCC,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'ajouter-stage-observation',
                        'modifier-stage-observation',
                        'historiser-stage-observation',
                        'restaurer-stage-observation',
                        'detruire-stage-observation',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_GESTION_CCC,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'ajouter-tutorat',
                        'modifier-tutorat',
                        'historiser-tutorat',
                        'restaurer-tutorat',
                        'detruire-tutorat',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_GESTION_CCC,
                    ],
                ],
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

                    /** PPP *******************************************************************************************/

                    'ppp' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ppp',
                            'defaults' => [
                                'controller' => AgentController::class,
                            ],
                        ],
                        'may_terminate' => 'false',
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'ajouter-ppp'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:ppp',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'modifier-ppp'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:ppp',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'historiser-ppp'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:ppp',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'restaurer-ppp'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:ppp',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'detruire-ppp'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'stageobs' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/stage-observation',
                            'defaults' => [
                                'controller' => AgentController::class,
                            ],
                        ],
                        'may_terminate' => 'false',
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'ajouter-stage-observation'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'modifier-stage-observation'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'historiser-stage-observation'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'restaurer-stage-observation'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:stageobs',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'detruire-stage-observation'
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'tutorat' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/stage-tutorat',
                            'defaults' => [
                                'controller' => AgentController::class,
                            ],
                        ],
                        'may_terminate' => 'false',
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'ajouter-tutorat'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'modifier-tutorat'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'historiser-tutorat'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'restaurer-tutorat'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:tutorat',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'detruire-tutorat'
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'accompagnement' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/accompagnement',
                            'defaults' => [
                                'controller' => AgentController::class,
                            ],
                        ],
                        'may_terminate' => 'false',
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'ajouter-accompagnement'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'modifier-accompagnement'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'historiser-accompagnement'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'restaurer-accompagnement'
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:accompagnement',
                                    'defaults' => [
                                        'controller' => AgentController::class,
                                        'action' => 'detruire-accompagnement'
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
                    'ressource' => [
                        'pages' => [
                            'agent' => [
                                'label'    => 'Agents',
                                'route'    => 'agent',
                                'resource' => PrivilegeController::getResourceId(AgentController::class, 'index') ,
                                'order'    => 100,
                            ],
                        ],
                    ],
                    'donnees' => [
                        'order' => 0100,
                        'label' => 'Mes données',
                        'title' => "Gestion des données d'un agent",
                        'route' => 'agent/afficher',
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
            AgentAffectationService::class => AgentAffectationServiceFactory::class,
            AgentAccompagnementService::class => AgentAccompagnementServiceFactory::class,
            AgentGradeService::class => AgentGradeServiceFactory::class,
            AgentPPPService::class => AgentPPPServiceFactory::class,
            AgentStageObservationService::class => AgentStageObservationServiceFactory::class,
            AgentStatutService::class => AgentStatutServiceFactory::class,
            AgentTutoratService::class => AgentTutoratServiceFactory::class,
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
            AgentAccompagnementForm::class => AgentAccompagnementFormFactory::class,
            AgentPPPForm::class => AgentPPPFormFactory::class,
            AgentStageObservationForm::class => AgentStageObservationFormFactory::class,
            AgentTutoratForm::class => AgentTutoratFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SelectionAgentHydrator::class => SelectionAgentHydratorFactory::class,
            AgentAccompagnementHydrator::class => AgentAccompagnementHydratorFactory::class,
            AgentPPPHydrator::class => AgentPPPHydratorFactory::class,
            AgentStageObservationHydrator::class => AgentStageObservationHydratorFactory::class,
            AgentTutoratHydrator::class => AgentTutoratHydratorFactory::class,
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