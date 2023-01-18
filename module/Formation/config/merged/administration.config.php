<?php

namespace Formation;

use Formation\Controller\AdministrationController;
use Formation\Controller\AdministrationControllerFactory;
use UnicaenParametre\Controller\CategorieController;
use UnicaenParametre\Controller\ParametreController;
use UnicaenParametre\Provider\Privilege\ParametrecategoriePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenRenderer\Controller\IndexController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AdministrationController::class,
                    'action' => [
                        'parametre',
                    ],
                    'privileges' => [
                        ParametrecategoriePrivileges::PARAMETRECATEGORIE_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'label' => 'Administration',
                        'route' => 'home',
                        'resources' => [
                            PrivilegeController::getResourceId(ParametreController::class, 'index'),
                        ],
                        'order'    => 20000,
                        'dropdown-header' => true,
                        'pages' => [
                            'parametre' => [
                                'order' => 1000,
                                'label' => 'Paramètres',
                                'route' => 'formation/administration/parametre',
                                'resource' => PrivilegeController::getResourceId(CategorieController::class, 'index'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            'template' => [
                                'order' => 2000,
                                'label' => 'Templates et macros',
                                'route' => 'contenu/template',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
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
            'formation' => [
                'child_routes' => [
                    'administration' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/administration',
                            'defaults' => [
                                'controller' => AdministrationController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'parametre' => [
                                'type'  => Segment::class,
                                'options' => [
                                    /** @see AdministrationController::parametreAction() */
                                    'route'    => '/parametre',
                                    'defaults' => [
                                        'controller' => AdministrationController::class,
                                        'action'     => 'parametre',
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
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            AdministrationController::class => AdministrationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];