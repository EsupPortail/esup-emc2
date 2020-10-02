<?php

namespace Application;

use Application\Controller\FormationController;
use Application\Controller\FormationControllerFactory;
use Application\Controller\FormationInstanceController;
use Application\Controller\FormationInstanceControllerFactory;
use Application\Form\AjouterFormation\AjouterFormationForm;
use Application\Form\AjouterFormation\AjouterFormationFormFactory;
use Application\Form\AjouterFormation\AjouterFormationHydrator;
use Application\Form\AjouterFormation\AjouterFormationHydratorFactory;
use Application\Form\Formation\FormationForm;
use Application\Form\Formation\FormationFormFactory;
use Application\Form\Formation\FormationHydrator;
use Application\Form\Formation\FormationHydratorFactory;
use Application\Form\FormationGroupe\FormationGroupeForm;
use Application\Form\FormationGroupe\FormationGroupeFormFactory;
use Application\Form\FormationGroupe\FormationGroupeHydrator;
use Application\Form\FormationGroupe\FormationGroupeHydratorFactory;
use Application\Form\FormationInstance\FormationInstanceForm;
use Application\Form\FormationInstance\FormationInstanceFormFactory;
use Application\Form\FormationInstance\FormationInstanceHydrator;
use Application\Form\FormationInstance\FormationInstanceHydratorFactory;
use Application\Form\FormationJournee\FormationJourneeForm;
use Application\Form\FormationJournee\FormationJourneeFormFactory;
use Application\Form\FormationJournee\FormationJourneeHydrator;
use Application\Form\FormationJournee\FormationJourneeHydratorFactory;
use Application\Form\SelectionFormation\SelectionFormationForm;
use Application\Form\SelectionFormation\SelectionFormationFormFactory;
use Application\Form\SelectionFormation\SelectionFormationHydrator;
use Application\Provider\Privilege\FormationPrivileges;
use Application\Service\Formation\FormationGroupeService;
use Application\Service\Formation\FormationGroupeServiceFactory;
use Application\Service\Formation\FormationService;
use Application\Service\Formation\FormationServiceFactory;
use Application\Service\Formation\FormationThemeService;
use Application\Service\Formation\FormationThemeServiceFactory;
use Application\Service\FormationInstance\FormationInstanceInscritService;
use Application\Service\FormationInstance\FormationInstanceInscritServiceFactory;
use Application\Service\FormationInstance\FormationInstanceJourneeService;
use Application\Service\FormationInstance\FormationInstanceJourneeServiceFactory;
use Application\Service\FormationInstance\FormationInstanceService;
use Application\Service\FormationInstance\FormationInstanceServiceFactory;
use Application\View\Helper\FormationGroupeViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INDEX,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'afficher',

                        'afficher-groupe',
                        'afficher-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'ajouter',
                        'ajouter-groupe',
                        'ajouter-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'editer',
                        'editer-groupe',
                        'editer-theme',
                        'update-ordre-groupe'
                        'modifier-formation-informations',
                        'ajouter-instance',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_EDITER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                        'historiser-groupe',
                        'restaurer-groupe',
                        'historiser-theme',
                        'restaurer-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'detruire',
                        'detruire-groupe',
                        'detruire-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_DETRUIRE,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INSTANCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'modifier-informations',

                        'ajouter-journee',
                        'modifier-journee',
                        'historiser-journee',
                        'restaurer-journee',
                        'supprimer-journee',

                        'ajouter-agent',
                        'historiser-agent',
                        'restaurer-agent',
                        'supprimer-agent',
                        'envoyer-liste-principale',
                        'envoyer-liste-complementaire',

                        'export-emargement',
                        'export-tous-emargements',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INSTANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'restaurer',
                        'historiser',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INSTANCE_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INSTANCE_SUPPRIMER,
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
                            'formation' => [
                                'label'    => 'Formations',
                                'route'    => 'formation',
                                'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_AFFICHER),
                                'order'    => 700,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-instance' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-instance',
                    'defaults' => [
                        'controller' => FormationInstanceController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter/:formation',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-informations' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-informations/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'modifier-informations',
                            ],
                        ],
                    ],
                    'ajouter-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-journee/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'ajouter-journee',
                            ],
                        ],
                    ],
                    'modifier-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'modifier-journee',
                            ],
                        ],
                    ],
                    'historiser-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'historiser-journee',
                            ],
                        ],
                    ],
                    'restaurer-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'restaurer-journee',
                            ],
                        ],
                    ],
                    'supprimer-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'supprimer-journee',
                            ],
                        ],
                    ],
                    'export-emargement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/export-emargement/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'export-emargement',
                            ],
                        ],
                    ],
                    'export-tous-emargements' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/export-tous-emargements/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'export-tous-emargements',
                            ],
                        ],
                    ],
                    'ajouter-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'ajouter-agent',
                            ],
                        ],
                    ],
                    'historiser-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'historiser-agent',
                            ],
                        ],
                    ],
                    'restaurer-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'restaurer-agent',
                            ],
                        ],
                    ],
                    'supprimer-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'supprimer-agent',
                            ],
                        ],
                    ],
                    'envoyer-liste-principale' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-liste-principale/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'envoyer-liste-principale',
                            ],
                        ],
                    ],
                    'envoyer-liste-complementaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-liste-complementaire/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'envoyer-liste-complementaire',
                            ],
                        ],
                    ],
                ],
            ],
            'formation-theme' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-theme',
                    'defaults' => [
                        'controller' => FormationController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'afficher-theme',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter-theme',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'editer-theme',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'historiser-theme',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'restaurer-theme',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'detruire-theme',
                            ],
                        ],
                    ],
                ],
            ],
            'formation-groupe' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-groupe',
                    'defaults' => [
                        'controller' => FormationController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'update-ordre-groupe' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/update-ordre-groupe/:ordre',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'update-ordre-groupe',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'afficher-groupe',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter-groupe',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'editer-groupe',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'historiser-groupe',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'restaurer-groupe',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'detruire-groupe',
                            ],
                        ],
                    ],
                ],
            ],
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
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'afficher',
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
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationService::class => FormationServiceFactory::class,
            FormationInstanceService::class => FormationInstanceServiceFactory::class,
            FormationInstanceInscritService::class => FormationInstanceInscritServiceFactory::class,
            FormationInstanceJourneeService::class => FormationInstanceJourneeServiceFactory::class,
            FormationGroupeService::class => FormationGroupeServiceFactory::class,
            FormationThemeService::class => FormationThemeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationController::class => FormationControllerFactory::class,
            FormationInstanceController::class => FormationInstanceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AjouterFormationForm::class => AjouterFormationFormFactory::class,
            FormationForm::class => FormationFormFactory::class,
            FormationGroupeForm::class => FormationGroupeFormFactory::class,
            FormationInstanceForm::class => FormationInstanceFormFactory::class,
            FormationJourneeForm::class => FormationJourneeFormFactory::class,
            SelectionFormationForm::class => SelectionFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            SelectionFormationHydrator::class => SelectionFormationHydrator::class,
//            FormationGroupeHydrator::class => FormationGroupeHydrator::class,
        ],
        'factories' => [
            AjouterFormationHydrator::class => AjouterFormationHydratorFactory::class,
            FormationHydrator::class => FormationHydratorFactory::class,
            FormationGroupeHydrator::class => FormationGroupeHydratorFactory::class,
            FormationInstanceHydrator::class => FormationInstanceHydratorFactory::class,
            FormationJourneeHydrator::class => FormationJourneeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formationGroupe' => FormationGroupeViewHelper::class,
        ],
    ],
];