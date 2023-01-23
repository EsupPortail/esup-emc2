<?php

namespace Formation;

use Formation\Controller\AdministrationController;
use Formation\Controller\AdministrationControllerFactory;
use UnicaenParametre\Controller\CategorieController;
use UnicaenParametre\Provider\Privilege\ParametrecategoriePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Provider\Privilege\PrivilegePrivileges;
use UnicaenRenderer\Controller\IndexController;
use UnicaenRenderer\Provider\Privilege\DocumenttemplatePrivileges;

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
                [
                    'controller' => AdministrationController::class,
                    'action' => [
                        'privilege',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_AFFECTER,
                    ],
                ],
                [
                    'controller' => AdministrationController::class,
                    'action' => [
                        'template',
                    ],
                    'privileges' => [
                        DocumenttemplatePrivileges::DOCUMENTTEMPLATE_INDEX,
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
                            PrivilegeController::getResourceId(AdministrationController::class, 'parametre'),
                            PrivilegeController::getResourceId(IndexController::class, 'index'),
                        ],
                        'order'    => 20000,
                        'dropdown-header' => true,
                        'pages' => [
                            'parametre' => [
                                'order' => 1000,
                                'label' => 'Paramètres',
                                'route' => 'formation/administration/parametre',
                                'resource' => ParametrecategoriePrivileges::getResourceId(ParametrecategoriePrivileges::PARAMETRECATEGORIE_INDEX),
                                'icon' => 'fas fa-angle-right',
                            ],
                            'privilege' => [
                                'order' => 1100,
                                'label' => 'Privilèges',
                                'route' => 'formation/administration/privilege',
                                'resource' => PrivilegePrivileges::getResourceId(PrivilegePrivileges::PRIVILEGE_AFFECTER),
                                'icon' => 'fas fa-angle-right',
                            ],
                            'template' => [
                                'order' => 2000,
                                'label' => 'Templates et macros',
                                'route' => 'formation/administration/template',
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
                            'privilege' => [
                                'type'  => Segment::class,
                                'options' => [
                                    /** @see AdministrationController::privilegeAction() */
                                    'route'    => '/privilege',
                                    'defaults' => [
                                        'controller' => AdministrationController::class,
                                        'action'     => 'privilege',
                                    ],
                                ],
                            ],
                            'template' => [
                                'type'  => Segment::class,
                                'options' => [
                                    /** @see AdministrationController::templateAction() */
                                    'route'    => '/template',
                                    'defaults' => [
                                        'controller' => AdministrationController::class,
                                        'action'     => 'template',
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