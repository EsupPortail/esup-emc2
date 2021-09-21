<?php

use UnicaenDocument\Controller\IndexController;
use UnicaenDocument\Controller\IndexControllerFactory;
use UnicaenDocument\Provider\Privilege\DocumentcontentPrivileges;
use UnicaenDocument\Provider\Privilege\DocumentmacroPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

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
                        DocumentcontentPrivileges::DOCUMENTCONTENU_INDEX,
                        DocumentmacroPrivileges::DOCUMENTMACRO_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'contenu' => [
                                'label' => 'Contenu',
                                'route' => 'contenu',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'order'    => 10001,
                                'pages' => [
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'contenu' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/contenu',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [ ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ]
    ],
];
