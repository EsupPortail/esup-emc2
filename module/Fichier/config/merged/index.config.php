<?php

namespace Application;

use Fichier\Controller\IndexController;
use Fichier\Controller\IndexControllerFactory;
use Fichier\Provider\Privilege\FichierPrivileges;
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
                        FichierPrivileges::FICHIER_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'index-fichier' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/index-fichier',
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