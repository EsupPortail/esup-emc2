<?php

namespace UnicaenParametre;

use UnicaenParametre\Controller\CategorieController;
use UnicaenParametre\Controller\CategorieControllerFactory;
use UnicaenParametre\Form\Categorie\CategorieForm;
use UnicaenParametre\Form\Categorie\CategorieFormFactory;
use UnicaenParametre\Form\Categorie\CategorieHydrator;
use UnicaenParametre\Form\Categorie\CategorieHydratorFactory;
use UnicaenParametre\Provider\Privilege\ParametrecategoriePrivileges;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Categorie\CategorieServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'index',
                    ],
                    'pivileges' => ParametrecategoriePrivileges::PARAMETRECATEGORIE_INDEX,
                ],
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'pivileges' => ParametrecategoriePrivileges::PARAMETRECATEGORIE_AJOUTER,
                ],
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'modifier',
                    ],
                    'pivileges' => ParametrecategoriePrivileges::PARAMETRECATEGORIE_MODIFIER,
                ],
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'pivileges' => ParametrecategoriePrivileges::PARAMETRECATEGORIE_SUPPRIMER,
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'parametre' => [
                                'label'    => 'Paramètres',
                                'route'    => 'parametre/index',
        //                            'resource' => PrivilegeController::getResourceId(CategorieController::class, 'index'),
                                'resource' => ParametrecategoriePrivileges::getResourceId(ParametrecategoriePrivileges::PARAMETRECATEGORIE_INDEX),
                                'order'    => 3000,
                                'pages' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'parametre' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/parametre',
                    'defaults' => [
                        'controller' => CategorieController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'index' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/index[/:categorie]',
                            'defaults' => [
                                'controller' => CategorieController::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'categorie' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/categorie',
                            'defaults' => [
                                'controller' => CategorieController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => CategorieController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:categorie',
                                    'defaults' => [
                                        'controller' => CategorieController::class,
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:categorie',
                                    'defaults' => [
                                        'controller' => CategorieController::class,
                                        'action' => 'supprimer'
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