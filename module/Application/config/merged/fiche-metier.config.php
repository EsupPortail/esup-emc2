<?php

namespace Application;

use Application\Controller\FicheMetierController;
use Application\Controller\FicheMetierControllerFactory;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\ActiviteExistanteFormFactory;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\FicheMetier\LibelleFormFactory;
use Application\Form\FicheMetier\LibelleHydrator;
use Application\Form\FicheMetier\LibelleHydratorFactory;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetier\FicheMetierServiceFactory;
use Application\View\Helper\FicheMetierEtatViewHelper;
use Application\View\Helper\FicheMetierExterneViewHelper;
use Application\View\Helper\FicheMetierViewHelper;
use Application\View\Helper\RaisonsViewHelper;
use Application\View\Helper\SpecificitePosteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        'afficher',
                        'exporter',
                        'exporter-toutes',
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
//                        'ajouter-terminer',
                        'ajouter-avec-metier',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'editer',

                        'editer-libelle',
                        'retirer-activite',
                        'deplacer-activite',
                        'ajouter-nouvelle-activite',
                        'ajouter-activite-existante',

                        'changer-expertise',
                        'changer-etat',
                        'modifier-application',
                        'modifier-formation',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_DETRUIRE,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'ajouter-etat',
                        'modifier-etat',
                        'supprimer-etat',
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
                            'fiche-metier' => [
                                'label' => 'Fiches métiers',
                                'route' => 'fiche-metier-type',
                                'resource' =>  FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX) ,
                                'order'    => 500,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
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
                    'etat' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/etat',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => FicheMetierController::class,
                                        'action'     => 'ajouter-etat',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:etat',
                                    'defaults' => [
                                        'controller' => FicheMetierController::class,
                                        'action'     => 'modifier-etat',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:etat',
                                    'defaults' => [
                                        'controller' => FicheMetierController::class,
                                        'action'     => 'supprimer-etat',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
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
                    'ajouter-avec-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-avec-metier/:metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'ajouter-avec-metier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher',
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
                    'exporter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/exporter/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'exporter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'exporter-toutes' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/exporter-toutes',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'exporter-toutes',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer[/:id]',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'detruire',
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
                    'changer-etat' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/changer-etat/:fiche-metier',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'changer-etat',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer-libelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer-libelle/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer-libelle',
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
            ActiviteExistanteForm::class => ActiviteExistanteFormFactory::class,
            LibelleForm::class => LibelleFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            LibelleHydrator::class => LibelleHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'specificitePoste' => SpecificitePosteViewHelper::class,
            'ficheMetierExterne' => FicheMetierExterneViewHelper::class,
            'ficheMetierEtat' => FicheMetierEtatViewHelper::class,
            'ficheMetier'  => FicheMetierViewHelper::class,
            'raisons' => RaisonsViewHelper::class,
        ],
    ],

];