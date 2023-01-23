<?php

namespace Formation;

use Formation\Assertion\FormationInstanceInscritAssertion;
use Formation\Assertion\FormationInstanceInscritAssertionFactory;
use Formation\Controller\FormationController;
use Formation\Controller\FormationControllerFactory;
use Formation\Controller\IndexController;
use Formation\Form\Formation\FormationForm;
use Formation\Form\Formation\FormationFormFactory;
use Formation\Form\Formation\FormationHydrator;
use Formation\Form\Formation\FormationHydratorFactory;
use Formation\Form\FormationElement\FormationElementForm;
use Formation\Form\FormationElement\FormationElementFormFactory;
use Formation\Form\FormationElement\FormationElementHydrator;
use Formation\Form\FormationElement\FormationElementHydratorFactory;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Formation\Form\SelectionFormation\SelectionFormationFormFactory;
use Formation\Form\SelectionFormation\SelectionFormationHydrator;
use Formation\Form\SelectionFormation\SelectionFormationHydratorFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Service\Formation\FormationService;
use Formation\Service\Formation\FormationServiceFactory;
use Formation\Service\FormationElement\FormationElementService;
use Formation\Service\FormationElement\FormationElementServiceFactory;
use Formation\Service\HasFormationCollection\HasFormationCollectionService;
use Formation\Service\HasFormationCollection\HasFormationCollectionServiceFactory;
use Formation\View\Helper\FormationInformationsViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Inscrit' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            FormationPrivileges::FORMATION_QUESTIONNAIRE_VISUALISER,
                            FormationPrivileges::FORMATION_QUESTIONNAIRE_MODIFIER,
                        ],
                        'resources' => ['Inscrit'],
                        'assertion' => FormationInstanceInscritAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_ACCES,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'rechercher-formation',
                        'rechercher-formateur',
                    ],
                    'roles' => [],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'editer',
                        'modifier-formation-informations',
                        'ajouter-instance',
                        'ajouter-application-element',
                        'ajouter-competence-element',
                        'ajouter-plan-de-formation',
                        'retirer-plan-de-formation',

                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'detruire',
                        'dedoublonner',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'order' => 1000,
                        'label' => 'Gestion des formations',
                        'title' => "Gestion des formations",
                        'route' => 'gestion',
                        'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                        'dropdown-header' => true,
                        'pages' => [
                            'formation_header' => [
                                'order' => 200,
                                'label' => 'Gestion des formations',
                                'route' => 'formation',
                                'resource' => PrivilegeController::getResourceId(FormationController::class, 'index'),
                                'icon' => 'fas fa-angle-right',
                                'dropdown-header' => true,
                            ],
                            'action-formation' => [
                                'order' => 220,
                                'label' => 'Actions de formation',
                                'route' => 'formation',
                                'resource' => PrivilegeController::getResourceId(FormationController::class, 'index'),
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
            'formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation',
                    'defaults' => [
                        'controller' => FormationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'modifier-formation-informations' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formation-informations/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'modifier-formation-informations',
                            ],
                        ],
                    ],
                    'ajouter-application-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-application-element/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter-application-element',
                            ],
                        ],
                    ],
                    'ajouter-competence-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-competence-element/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter-competence-element',
                            ],
                        ],
                    ],
                    'ajouter-plan-de-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-plan-de-formation/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter-plan-de-formation',
                            ],
                        ],
                    ],
                    'retirer-plan-de-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-plan-de-formation/:formation/:plan-de-formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'retirer-plan-de-formation',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'dedoublonner' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dedoublonner/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'dedoublonner',
                            ],
                        ],
                    ],
                    'rechercher-formation' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/rechercher-formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'rechercher-formation',
                            ],
                        ],
                    ],
                    'rechercher-formateur' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/rechercher-formateur',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'rechercher-formateur',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceInscritAssertion::class => FormationInstanceInscritAssertionFactory::class,
            FormationService::class => FormationServiceFactory::class,
            FormationElementService::class => FormationElementServiceFactory::class,
            HasFormationCollectionService::class => HasFormationCollectionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationController::class => FormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationForm::class => FormationFormFactory::class,
            FormationElementForm::class => FormationElementFormFactory::class,
            SelectionFormationForm::class => SelectionFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationHydrator::class => FormationHydratorFactory::class,
            FormationElementHydrator::class => FormationElementHydratorFactory::class,
            SelectionFormationHydrator::class => SelectionFormationHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formationInformations' => FormationInformationsViewHelper::class,
        ],
    ],

];