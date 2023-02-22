<?php

namespace Application;

use Application\Controller\FicheMetierController;
use Application\Controller\FicheMetierControllerFactory;
use Application\Form\FicheMetierImportation\FicheMetierImportationForm;
use Application\Form\FicheMetierImportation\FicheMetierImportationFormFactory;
use Application\Form\FicheMetierImportation\FichierMetierImportationHydrator;
use Application\Form\FicheMetierImportation\FichierMetierImportationHydratorFactory;
use Application\Form\SelectionFicheMetier\SelectionFicheMetierForm;
use Application\Form\SelectionFicheMetier\SelectionFicheMetierFormFactory;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetier\FicheMetierServiceFactory;
use Application\View\Helper\FicheMetierExterneViewHelper;
use Application\View\Helper\RaisonsViewHelper;
use Application\View\Helper\SpecificitePosteViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_INDEX,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'afficher-applications',
                        'afficher-competences',
                        'graphique-applications',
                        'graphique-competences',

                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'ajouter',
                        'importer-depuis-csv',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'retirer-activite',
                        'deplacer-activite',
                        'ajouter-nouvelle-activite',
                        'ajouter-activite-existante',

                        'changer-expertise',
                        'modifier-application',
                        'modifier-formation',

                        'cloner-applications',
                        'cloner-competences',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
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
                            'fiches' => [
                                'label' => 'Gestion des fiches',
                                'route' => 'fiche-metier-type',
                                'resource' =>  FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX) ,
                                'order'    => 2000,
                                'dropdown-header' => true,
                            ],
                            'fiche-metier' => [
                                'label' => 'Fiches métiers',
                                'route' => 'fiche-metier-type',
                                'resource' =>  FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX) ,
                                'order'    => 2020,
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
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                    'defaults' => [
                        'controller' => FicheMetierController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'fiche-metier-type' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier-type',
                    'defaults' => [
                        'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'dupliquer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dupliquer/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'dupliquer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'importer-depuis-csv' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/importer-depuis-csv[/:importation]',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'importer-depuis-csv',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher-applications' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-applications/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher-applications',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'cloner-applications' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/cloner-applications/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'cloner-applications',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher-competences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-competences/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher-competences',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'cloner-competences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/cloner-competences/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'cloner-competences',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
//                    'ajouter-terminer' => [
//                        'type'  => Segment::class,
//                        'options' => [
//                            'route'    => '/ajouter-terminer/:fiche',
//                            'defaults' => [
//                                'controller' => FicheMetierController::class,
//                                'action'     => 'ajouter-terminer',
//                            ],
//                        ],
//                        'may_terminate' => true,
//                    ],
                    'graphique-applications' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/graphique-applications/:fiche-metier[/:agent]',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'graphique-applications',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'graphique-competences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/graphique-competences/:fiche-metier[/:agent]',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'graphique-competences',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'changer-expertise' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/changer-expertise/:fiche',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'changer-expertise',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'retirer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-activite/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'retirer-activite',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'deplacer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/deplacer-activite/:id/:direction',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'deplacer-activite',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-nouvelle-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-nouvelle-activite/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'ajouter-nouvelle-activite',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-activite-existante' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-activite-existante/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'ajouter-activite-existante',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-application/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-application',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formation/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-formation',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            FicheMetierService::class => FicheMetierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SelectionFicheMetierForm::class => SelectionFicheMetierFormFactory::class,
            FicheMetierImportationForm::class => FicheMetierImportationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FichierMetierImportationHydrator::class => FichierMetierImportationHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'specificitePoste' => SpecificitePosteViewHelper::class,
            'ficheMetierExterne' => FicheMetierExterneViewHelper::class,
            'raisons' => RaisonsViewHelper::class,
        ],
    ],

];