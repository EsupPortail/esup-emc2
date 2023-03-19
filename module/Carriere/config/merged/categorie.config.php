<?php

namespace Carriere;

use Carriere\Controller\CategorieController;
use Carriere\Controller\CategorieControllerFactory;
use Carriere\Form\Categorie\CategorieForm;
use Carriere\Form\Categorie\CategorieFormFactory;
use Carriere\Form\Categorie\CategorieHydrator;
use Carriere\Form\Categorie\CategorieHydratorFactory;
use Carriere\Provider\Privilege\CategoriePrivileges;
use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Categorie\CategorieServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CategoriePrivileges::CATEGORIE_INDEX
                    ],
                ],
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'afficher-metiers',
                    ],
                    'privileges' => [
                        CategoriePrivileges::CATEGORIE_AFFICHER
                    ],
                ],
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                    ],
                    'privileges' => [
                        CategoriePrivileges::CATEGORIE_MODIFIER
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
                                'order' => 810,
                                'label' => 'Catégories',
                                'route' => 'categorie',
                                'resource' => PrivilegeController::getResourceId(CategorieController::class, 'index') ,
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
            'categorie' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/categorie',
                    'defaults' => [
                        /** @see CategorieController::indexAction(); */
                        'controller' => CategorieController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher-metiers' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-metiers/:categorie',
                            'defaults' => [
                                /** @see CategorieController::afficherMetiersAction(); */
                                'controller' => CategorieController::class,
                                'action'     => 'afficher-metiers',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see CategorieController::ajouterAction(); */
                                'controller' => CategorieController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:categorie',
                            'defaults' => [
                                /** @see CategorieController::modifierAction() */
                                'controller' => CategorieController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:categorie',
                            'defaults' => [
                                /** @see CategorieController::historiserAction() */
                                'controller' => CategorieController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:categorie',
                            'defaults' => [
                                /** @see CategorieController::restaurerAction() */
                                'controller' => CategorieController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:categorie',
                            'defaults' => [
                                /** @see CategorieController::supprimerAction() */
                                'controller' => CategorieController::class,
                                'action'     => 'supprimer',
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
        ],
    ],
    'controllers'     => [
        'factories' => [
            CategorieController::class => CategorieControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CategorieForm::class => CategorieFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CategorieHydrator::class => CategorieHydratorFactory::class,
        ],
    ]

];