<?php

namespace Autoform;

use Autoform\Controller\IndexController;
use Autoform\Controller\IndexControllerFactory;
use Autoform\Provider\Privilege\IndexPrivileges;
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
                        IndexPrivileges::AFFICHER_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'autoform' => [
                'type' => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/autoform',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
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