<?php

namespace Carriere;

use Carriere\Controller\IndexController;
use Carriere\Controller\IndexControllerFactory;
use Carriere\Provider\Privilege\CategoriePrivileges;
use Carriere\Provider\Privilege\CorpsPrivileges;
use Carriere\Provider\Privilege\CorrespondancePrivileges;
use Carriere\Provider\Privilege\GradePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CategoriePrivileges::CATEGORIE_INDEX,
                        CorpsPrivileges::CORPS_INDEX,
                        CorrespondancePrivileges::CORRESPONDANCE_INDEX,
                        GradePrivileges::GRADE_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'carriere' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/carriere',
                    'defaults' => [
                        /** @see IndexController::indexAction() */
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
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
                                'order' => 800,
                                'label' => 'Gestion de la carrière',
                                'route' => 'carriere',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index') ,
                                'icon' => 'fas fa-angle-right',
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];