<?php

namespace Application;

use Application\Controller\CategorieController;
use Application\Controller\CategorieControllerFactory;
use Application\Controller\CorpsController;
use Application\Form\Categorie\CategorieForm;
use Application\Form\Categorie\CategorieFormFactory;
use Application\Form\Categorie\CategorieHydrator;
use Application\Form\Categorie\CategorieHydratorFactory;
use Application\Provider\Privilege\CorpsPrivileges;
use Application\Service\Categorie\CategorieService;
use Application\Service\Categorie\CategorieServiceFactory;
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
                    'privileges' => [
                        CorpsPrivileges::CORPS_INDEX,
                    ],
                ],
                [
                    'controller' => CategorieController::class,
                    'action' => [
                        'afficher-metiers',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_AFFICHER,
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
                        CorpsPrivileges::CORPS_MODIFIER,
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
                                'resource' => PrivilegeController::getResourceId(CorpsController::class, 'index') ,
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