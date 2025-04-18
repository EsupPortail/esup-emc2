<?php

namespace Element;

use Element\Controller\CompetenceController;
use Element\Controller\CompetenceControllerFactory;
use Element\Form\Competence\CompetenceForm;
use Element\Form\Competence\CompetenceFormFactory;
use Element\Form\Competence\CompetenceHydrator;
use Element\Form\Competence\CompetenceHydratorFactory;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Form\SelectionCompetence\SelectionCompetenceFormFactory;
use Element\Form\SelectionCompetence\SelectionCompetenceHydrator;
use Element\Form\SelectionCompetence\SelectionCompetenceHydratorFactory;
use Element\Provider\Privilege\CompetencePrivileges;
use Element\Service\Competence\CompetenceService;
use Element\Service\Competence\CompetenceServiceFactory;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceFactory;
use Element\View\Helper\CompetenceBlocViewHelper;
use Element\View\Helper\CompetenceBlocViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_HISTORISER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_EFFACER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'substituer'
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_SUBSTITUER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'rechercher',
                    ],
                    'roles' => [],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'rechercher-agents',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'competence' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence',
                            'defaults' => [
                                /** @see CompetenceController::indexAction() */
                                'controller' => CompetenceController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:competence',
                                    'defaults' => [
                                        /** @see CompetenceController::afficherAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:competence-type]',
                                    'defaults' => [
                                        /** @see CompetenceController::ajouterAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:competence',
                                    'defaults' => [
                                        /** @see CompetenceController::modifierAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:competence',
                                    'defaults' => [
                                        /** @see CompetenceController::historiserAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:competence',
                                    'defaults' => [
                                        /** @see CompetenceController::restaurerAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:competence',
                                    'defaults' => [
                                        /** @see CompetenceController::detruireAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'detruire',
                                    ],
                                ],
                            ],
                            'substituer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/substituer/:competence',
                                    'defaults' => [
                                        /** @see CompetenceController::substituerAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'substituer',
                                    ],
                                ],
                            ],
                            'rechercher' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher',
                                    'defaults' => [
                                        /** @see CompetenceController::rechercherAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'rechercher',
                                    ],
                                ],
                            ],
                            'rechercher-agents' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher-agents',
                                    'defaults' => [
                                        /** @see CompetenceController::rechercherAgentsAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'rechercher-agents',
                                    ],
                                ],
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
                    'ressource' => [
                        'pages' => [
                            [
                                'label' => 'Compétences',
                                'route' => 'element/competence',
                                'resource' => PrivilegeController::getResourceId(CompetenceController::class, 'index') ,
                                'order' => 3200,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'service_manager' => [
        'factories' => [
            CompetenceService::class => CompetenceServiceFactory::class,
            HasCompetenceCollectionService::class => HasCompetenceCollectionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CompetenceController::class => CompetenceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceForm::class => CompetenceFormFactory::class,
            SelectionCompetenceForm::class => SelectionCompetenceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceHydrator::class => CompetenceHydratorFactory::class,
            SelectionCompetenceHydrator::class => SelectionCompetenceHydratorFactory::class,

        ],
    ],
    'view_helpers' => [
        'factories' => [
            CompetenceBlocViewHelper::class => CompetenceBlocViewHelperFactory::class,
        ],
        'aliases' => [
            'competenceBloc' => CompetenceBlocViewHelper::class,
        ],
    ],

];