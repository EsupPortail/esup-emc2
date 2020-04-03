<?php

namespace Application;

use Application\Controller\AdministrationController;
use Application\Controller\AdministrationControllerFactory;
use Application\Provider\Privilege\AdministrationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AdministrationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        AdministrationPrivileges::AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'administration' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller' => AdministrationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
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