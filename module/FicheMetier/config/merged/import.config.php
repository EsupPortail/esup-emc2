<?php

namespace FicheMetier;

use FicheMetier\Controller\ImportController;
use FicheMetier\Controller\ImportControllerFactory;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ImportController::class,
                    'action' => [
                        'import',
                    ],
                    'roles' => [

                    ],
                ],
            ],
        ],
    ],


    'router' => [
        'routes' => [
            'fiche-metier' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/fiche-metier',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'import' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/import',
                            'defaults' => [
                                /** @see ImportController::importAction() */
                                'controller' => ImportController::class,
                                'action' => 'import',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers' => [
        'factories' => [
            ImportController::class => ImportControllerFactory::class
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
    'view_helpers' => [
        'invokables' => [
        ],
    ]

];