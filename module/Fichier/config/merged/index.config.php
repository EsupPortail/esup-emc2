<?php

namespace Application;

use Fichier\Controller\Index\IndexController;
use Fichier\Controller\Index\IndexControllerFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Utilisateur\Entity\Db\Role;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [
                        'Administrateur technique',
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