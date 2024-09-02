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
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireForm;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireFormFactory;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireHydrator;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireHydratorFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\ActionType\ActionTypeService;
use Formation\Service\ActionType\ActionTypeServiceFactory;
use Formation\Service\Formation\FormationService;
use Formation\Service\Formation\FormationServiceFactory;
use Formation\Service\FormationElement\FormationElementService;
use Formation\Service\FormationElement\FormationElementServiceFactory;
use Formation\Service\HasFormationCollection\HasFormationCollectionService;
use Formation\Service\HasFormationCollection\HasFormationCollectionServiceFactory;
use Formation\View\Helper\FormationInformationsViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Inscrit' => [],
            ],
        ],
        'rule_providers' => [
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
                        'rechercher-formations-actives',
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
                        'afficher',
                        'fiche',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AFFICHER,
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
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index-gestionnaire',
                    ],
                    'roles' => [
                        FormationRoles::GESTIONNAIRE_FORMATION,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'resultat-enquete',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
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

    'router' => [
        'routes' => [
            'index-mes-formations' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/index-mes-formations',
                    'defaults' => [
                        /** @see IndexController::indexAction() */
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'index-gestionnaire' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/index-gestionnaire',
                    'defaults' => [
                        /** @see IndexController::indexGestionnaireAction **/
                        'controller' => IndexController::class,
                        'action'     => 'index-gestionnaire',
                    ],
                ],
                'may_terminate' => true,
            ],
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                    'defaults' => [
                        'controller' => FormationController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:formation',
                            'defaults' => [
                                /** @see FormationController::afficherAction() */
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'fiche' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/fiche/:formation',
                            'defaults' => [
                                /** @see FormationController::ficheAction() */
                                'action' => 'fiche',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/editer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'editer',
                            ],
                        ],
                    ],
                    'modifier-formation-informations' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-formation-informations/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'modifier-formation-informations',
                            ],
                        ],
                    ],
                    'ajouter-application-element' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ajouter-application-element/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'ajouter-application-element',
                            ],
                        ],
                    ],
                    'ajouter-competence-element' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ajouter-competence-element/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'ajouter-competence-element',
                            ],
                        ],
                    ],
                    'ajouter-plan-de-formation' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ajouter-plan-de-formation/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'ajouter-plan-de-formation',
                            ],
                        ],
                    ],
                    'retirer-plan-de-formation' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/retirer-plan-de-formation/:formation/:plan-de-formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'retirer-plan-de-formation',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/detruire/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'detruire',
                            ],
                        ],
                    ],
                    'dedoublonner' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dedoublonner/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'dedoublonner',
                            ],
                        ],
                    ],
                    'rechercher-formation' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/rechercher-formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'rechercher-formation',
                            ],
                        ],
                    ],
                    'rechercher-formations-actives' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/rechercher-formations-actives',
                            'defaults' => [
                                /** @see FormationController::rechercherFormationsActivesAction() */
                                'action' => 'rechercher-formations-actives',
                            ],
                        ],
                    ],
                    'rechercher-formateur' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/rechercher-formateur',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action' => 'rechercher-formateur',
                            ],
                        ],
                    ],
                    'resultat-enquete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/resultat-enquete/:formation',
                            'defaults' => [
                                /** @see FormationController::resultatEnqueteAction() */
                                'action' => 'resultat-enquete',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ActionTypeService::class => ActionTypeServiceFactory::class,
            FormationInstanceInscritAssertion::class => FormationInstanceInscritAssertionFactory::class,
            FormationService::class => FormationServiceFactory::class,
            FormationElementService::class => FormationElementServiceFactory::class,
            HasFormationCollectionService::class => HasFormationCollectionServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FormationController::class => FormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationForm::class => FormationFormFactory::class,
            FormationElementForm::class => FormationElementFormFactory::class,
            SelectionFormationForm::class => SelectionFormationFormFactory::class,
            SelectionGestionnaireForm::class => SelectionGestionnaireFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationHydrator::class => FormationHydratorFactory::class,
            FormationElementHydrator::class => FormationElementHydratorFactory::class,
            SelectionFormationHydrator::class => SelectionFormationHydratorFactory::class,
            SelectionGestionnaireHydrator::class => SelectionGestionnaireHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formationInformations' => FormationInformationsViewHelper::class,
        ],
    ],

];