<?php

namespace Application;

use Application\Controller\CategorieController;
use Application\Controller\CategorieControllerFactory;
use Application\Controller\CorpsController;
use Application\Controller\CorpsControllerFactory;
use Application\Form\Categorie\CategorieForm;
use Application\Form\Categorie\CategorieFormFactory;
use Application\Form\Categorie\CategorieHydrator;
use Application\Form\Categorie\CategorieHydratorFactory;
use Application\Provider\Privilege\CorpsPrivileges;
use Application\Service\Categorie\CategorieService;
use Application\Service\Categorie\CategorieServiceFactory;
use Application\Service\Corps\CorpsService;
use Application\Service\Corps\CorpsServiceFactory;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Correspondance\CorrespondanceServiceFactory;
use Application\Service\Grade\GradeService;
use Application\Service\Grade\GradeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CorpsController::class,
                    'action' => [
                        'index'
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_INDEX,
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
                        CorpsPrivileges::CORPS_INDEX,
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
                                'order' => 500,
                                'label' => 'Catégories, corps, grades et correspondances',
                                'route' => 'corps',
                                'resource' => PrivilegeController::getResourceId(CorpsController::class, 'index') ,
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'corps' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/corps',
                    'defaults' => [
                        'controller' => CorpsController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
            'categorie' => [
                /** CATEGORIE ***************************************************************************************/
                'type'  => Literal::class,
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
            CorpsService::class => CorpsServiceFactory::class,
            CorrespondanceService::class => CorrespondanceServiceFactory::class,
            GradeService::class => GradeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CategorieController::class => CategorieControllerFactory::class,
            CorpsController::class => CorpsControllerFactory::class,
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