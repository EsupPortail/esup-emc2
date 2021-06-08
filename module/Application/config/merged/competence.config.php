<?php

namespace Application;

use Application\Controller\CompetenceController;
use Application\Controller\CompetenceControllerFactory;
use Application\Controller\MaitriseNiveauController;
use Application\Controller\MaitriseNiveauControllerFactory;
use Application\Form\Competence\CompetenceForm;
use Application\Form\Competence\CompetenceFormFactory;
use Application\Form\Competence\CompetenceHydrator;
use Application\Form\Competence\CompetenceHydratorFactory;
use Application\Form\CompetenceElement\CompetenceElementForm;
use Application\Form\CompetenceElement\CompetenceElementFormFactory;
use Application\Form\CompetenceElement\CompetenceElementHydrator;
use Application\Form\CompetenceElement\CompetenceElementHydratorFactory;
use Application\Form\MaitriseNiveau\MaitriseNiveauForm;
use Application\Form\MaitriseNiveau\MaitriseNiveauFormFactory;
use Application\Form\MaitriseNiveau\MaitriseNiveauHydrator;
use Application\Form\MaitriseNiveau\MaitriseNiveauHydratorFactory;
use Application\Form\CompetenceType\CompetenceTypeForm;
use Application\Form\CompetenceType\CompetenceTypeFormFactory;
use Application\Form\CompetenceType\CompetenceTypeHydrator;
use Application\Form\CompetenceType\CompetenceTypeHydratorFactory;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Form\SelectionCompetence\SelectionCompetenceFormFactory;
use Application\Form\SelectionCompetence\SelectionCompetenceHydrator;
use Application\Form\SelectionMaitriseNiveau\SelectionMaitriseNiveauForm;
use Application\Form\SelectionMaitriseNiveau\SelectionMaitriseNiveauFormFactory;
use Application\Form\SelectionMaitriseNiveau\SelectionMaitriseNiveauHydrator;
use Application\Form\SelectionMaitriseNiveau\SelectionMaitriseNiveauHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\CompetencePrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\Competence\CompetenceService;
use Application\Service\Competence\CompetenceServiceFactory;
use Application\Service\CompetenceElement\CompetenceElementService;
use Application\Service\CompetenceElement\CompetenceElementServiceFactory;
use Application\Service\CompetenceTheme\CompetenceThemeService;
use Application\Service\CompetenceTheme\CompetenceThemeServiceFactory;
use Application\Service\CompetenceType\CompetenceTypeService;
use Application\Service\CompetenceType\CompetenceTypeServiceFactory;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionServiceFactory;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
use Application\Service\MaitriseNiveau\MaitriseNiveauServiceFactory;
use Application\View\Helper\CompetenceBlocViewHelper;
use Application\View\Helper\CompetenceBlocViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        'afficher-competence-type',
                        'afficher-competence-theme',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'ajouter',
                        'ajouter-competence-type',
                        'ajouter-competence-theme',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'importer',
                        'substituer',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'modifier-competence-type',
                        'historiser-competence-type',
                        'restaurer-competence-type',
                        'modifier-competence-theme',
                        'historiser-competence-theme',
                        'restaurer-competence-theme',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_EDITER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'detruire',
                        'detruire-competence-type',
                        'detruire-competence-theme',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_EFFACER,
                    ],
                ],
                [
                    'controller' => MaitriseNiveauController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => MaitriseNiveauController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => MaitriseNiveauController::class,
                    'action' => [
                        'modifier',
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_EDITER,
                    ],
                ],
                [
                    'controller' => MaitriseNiveauController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_EFFACER,
                    ],
                ],
                [
                    'controller' => CompetenceController::class,
                    'action' => [
                        'ajouter-competence-element',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'competence' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/competence',
                    'defaults' => [
                        'controller' => CompetenceController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter[/:competence-type]',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'importer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/importer',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'importer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'substituer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/substituer/:competence',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'substituer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:competence',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-competence-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-competence-element/:type/:id[/:clef]',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'ajouter-competence-element',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:competence',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:competence',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:competence',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:competence',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
            'competence-maitrise' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/competence-maitrise',
                    'defaults' => [
                        'controller' => MaitriseNiveauController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter/:type',
                            'defaults' => [
                                'controller' => MaitriseNiveauController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:maitrise',
                            'defaults' => [
                                'controller' => MaitriseNiveauController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:maitrise',
                            'defaults' => [
                                'controller' => MaitriseNiveauController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:maitrise',
                            'defaults' => [
                                'controller' => MaitriseNiveauController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:maitrise',
                            'defaults' => [
                                'controller' => MaitriseNiveauController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:maitrise',
                            'defaults' => [
                                'controller' => MaitriseNiveauController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
            'competence-theme' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/competence-theme',
                    'defaults' => [
                        'controller' => CompetenceController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'ajouter-competence-theme',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:competence-theme',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'afficher-competence-theme',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:competence-theme',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'modifier-competence-theme',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:competence-theme',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'historiser-competence-theme',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:competence-theme',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'restaurer-competence-theme',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:competence-theme',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'detruire-competence-theme',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
            'competence-type' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/competence-type',
                    'defaults' => [
                        'controller' => CompetenceController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'ajouter-competence-type',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:competence-type',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'afficher-competence-type',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:competence-type',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'modifier-competence-type',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:competence-type',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'historiser-competence-type',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:competence-type',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'restaurer-competence-type',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:competence-type',
                            'defaults' => [
                                'controller' => CompetenceController::class,
                                'action'     => 'detruire-competence-type',
                            ],
                        ],
                        'may_terminate' => true,
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
                            'competence' => [
                                'label'    => 'Compétences',
                                'route'    => 'competence',
                                'resource' => CompetencePrivileges::getResourceId(CompetencePrivileges::COMPETENCE_AFFICHER),
                                'order'    => 600,
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
            CompetenceElementService::class => CompetenceElementServiceFactory::class,
            HasCompetenceCollectionService::class => HasCompetenceCollectionServiceFactory::class,
            MaitriseNiveauService::class => MaitriseNiveauServiceFactory::class,
            CompetenceThemeService::class => CompetenceThemeServiceFactory::class,
            CompetenceTypeService::class => CompetenceTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CompetenceController::class => CompetenceControllerFactory::class,
            MaitriseNiveauController::class => MaitriseNiveauControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceForm::class => CompetenceFormFactory::class,
            MaitriseNiveauForm::class => MaitriseNiveauFormFactory::class,
            CompetenceTypeForm::class => CompetenceTypeFormFactory::class,
            CompetenceElementForm::class => CompetenceElementFormFactory::class,
            SelectionCompetenceForm::class => SelectionCompetenceFormFactory::class,
            SelectionMaitriseNiveauForm::class => SelectionMaitriseNiveauFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            SelectionCompetenceHydrator::class => SelectionCompetenceHydrator::class,
        ],
        'factories' => [
            CompetenceHydrator::class => CompetenceHydratorFactory::class,
            MaitriseNiveauHydrator::class => MaitriseNiveauHydratorFactory::class,
            CompetenceTypeHydrator::class => CompetenceTypeHydratorFactory::class,
            CompetenceElementHydrator::class => CompetenceElementHydratorFactory::class,
            SelectionMaitriseNiveauHydrator::class => SelectionMaitriseNiveauHydratorFactory::class,
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