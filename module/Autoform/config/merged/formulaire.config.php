<?php

namespace Autoform;

use Autoform\Controller\FormulaireController;
use Autoform\Controller\FormulaireControllerFactory;
use Autoform\Form\Categorie\CategorieForm;
use Autoform\Form\Categorie\CategorieFormFactory;
use Autoform\Form\Categorie\CategorieHydrator;
use Autoform\Form\Categorie\CategorieHydratorFactory;
use Autoform\Form\Formulaire\FormulaireForm;
use Autoform\Form\Formulaire\FormulaireFormFactory;
use Autoform\Form\Formulaire\FormulaireHydrator;
use Autoform\Form\Formulaire\FormulaireHydratorFactory;
use Autoform\Provider\Privilege\FormulairePrivileges;
use Autoform\Service\Categorie\CategorieService;
use Autoform\Service\Categorie\CategorieServiceFactory;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireInstanceServiceFactory;
use Autoform\Service\Formulaire\FormulaireReponseService;
use Autoform\Service\Formulaire\FormulaireReponseServiceFactory;
use Autoform\Service\Formulaire\FormulaireService;
use Autoform\Service\Formulaire\FormulaireServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormulaireController::class,
                    'action' => [
                        'index',
                        'afficher-formulaire',
                        'afficher-resultat',
                        'export-pdf',
                    ],
                    'privileges' => [
                        FormulairePrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => FormulaireController::class,
                    'action' => [
                        'creer',
                    ],
                    'privileges' => [
                        FormulairePrivileges::CREER,
                    ],
                ],
                [
                    'controller' => FormulaireController::class,
                    'action' => [
                        'modifier',
                        'modifier-description',
                        'ajouter-categorie',
                        'modifier-categorie',
                        'historiser-categorie',
                        'restaurer-categorie',
                        'detruire-categorie',
                        'bouger-categorie',

                        'ajouter-champ',
                        'modifier-champ',
                        'historiser-champ',
                        'restaurer-champ',
                        'detruire-champ',
                        'bouger-champ',
                    ],
                    'privileges' => [
                        FormulairePrivileges::MODIFIER,
                    ],
                ],
                [
                    'controller' => FormulaireController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FormulairePrivileges::HISTORISER,
                    ],
                ],
                [
                    'controller' => FormulaireController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        FormulairePrivileges::DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'autoform' => [
                'child_routes' => [
                    'formulaires' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/formulaires',
                            'defaults' => [
                                'controller' => FormulaireController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'creer-formulaire' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer-formulaire',
                            'defaults' => [
                                'controller' => FormulaireController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],

                    'formulaire' => [
                        'type' => Segment::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/formulaire/:formulaire',
                        ],
                        'child_routes' => [
                            'afficher-formulaire' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher-formulaire[/:instance]',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'afficher-formulaire',
                                    ],
                                ],
                            ],
                            'afficher-resultat' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher-resultat[/:instance]',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'afficher-resultat',
                                    ],
                                ],
                            ],
                            'modifier-description' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier-description',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'modifier-description',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                                'child_routes' => [
                                    'ajouter-categorie' => [
                                        'type' => Literal::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/ajouter-categorie',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'ajouter-categorie',
                                            ],
                                        ],
                                    ],
                                    'modifier-categorie' => [
                                        'type' => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/modifier-categorie/:categorie',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'modifier-categorie',
                                            ],
                                        ],
                                    ],
                                    'historiser-categorie' => [
                                        'type' => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/historiser-categorie/:categorie',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'ajouter-categorie',
                                            ],
                                        ],
                                    ],
                                    'restaurer-categorie' => [
                                        'type' => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/restaurer-categorie/:categorie',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'restaurer-categorie',
                                            ],
                                        ],
                                    ],
                                    'detruire-categorie' => [
                                        'type' => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/detruire-categorie/:categorie',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'detruire-categorie',
                                            ],
                                        ],
                                    ],
                                    'bouger-categorie' => [
                                        'type' => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/bouger-categorie/:categorie/:direction',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'bouger-categorie',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire',
                                    'defaults' => [
                                        'controller' => FormulaireController::class,
                                        'action'     => 'detruire',
                                    ],
                                ],
                            ],

                            'categorie' => [
                                'type' => Segment::class,
                                'may_terminate' => false,
                                'options' => [
                                    'route'    => '/categorie/:categorie',
                                ],
                                'child_routes' => [
                                    'ajouter-champ' => [
                                        'type' => Literal::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/ajouter-champ',
                                            'defaults' => [
                                                'controller' => FormulaireController::class,
                                                'action'     => 'ajouter-champ',
                                            ],
                                        ],
                                    ],
                                    'champ' => [
                                        'type' => Segment::class,
                                        'may_terminate' => false,
                                        'options' => [
                                            'route'    => '/champ/:champ',
                                        ],
                                        'child_routes' => [
                                            'modifier' => [
                                                'type' => Literal::class,
                                                'may_terminate' => true,
                                                'options' => [
                                                    'route'    => '/modifier',
                                                    'defaults' => [
                                                        'controller' => FormulaireController::class,
                                                        'action'     => 'modifier-champ',
                                                    ],
                                                ],
                                            ],
                                            'bouger' => [
                                                'type' => Segment::class,
                                                'may_terminate' => true,
                                                'options' => [
                                                    'route'    => '/bouger/:direction',
                                                    'defaults' => [
                                                        'controller' => FormulaireController::class,
                                                        'action'     => 'bouger-champ',
                                                    ],
                                                ],
                                            ],
                                            'historiser' => [
                                                'type' => Literal::class,
                                                'may_terminate' => true,
                                                'options' => [
                                                    'route'    => '/historiser',
                                                    'defaults' => [
                                                        'controller' => FormulaireController::class,
                                                        'action'     => 'historiser-champ',
                                                    ],
                                                ],
                                            ],
                                            'restaurer' => [
                                                'type' => Literal::class,
                                                'may_terminate' => true,
                                                'options' => [
                                                    'route'    => '/restaurer',
                                                    'defaults' => [
                                                        'controller' => FormulaireController::class,
                                                        'action'     => 'restaurer-champ',
                                                    ],
                                                ],
                                            ],
                                            'detruire' => [
                                                'type' => Literal::class,
                                                'may_terminate' => true,
                                                'options' => [
                                                    'route'    => '/detruire',
                                                    'defaults' => [
                                                        'controller' => FormulaireController::class,
                                                        'action'     => 'detruire-champ',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CategorieService::class => CategorieServiceFactory::class,
            FormulaireService::class => FormulaireServiceFactory::class,
            FormulaireInstanceService::class => FormulaireInstanceServiceFactory::class,
            FormulaireReponseService::class => FormulaireReponseServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormulaireController::class => FormulaireControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CategorieForm::class => CategorieFormFactory::class,
            FormulaireForm::class => FormulaireFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CategorieHydrator::class => CategorieHydratorFactory::class,
            FormulaireHydrator::class => FormulaireHydratorFactory::class,
        ],
    ],

    'view_helpers' => [
        'invokables' => [
        ],
    ],

];