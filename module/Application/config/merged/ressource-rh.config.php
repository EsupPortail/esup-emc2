<?php

namespace Application;

use Application\Controller\RessourceRhController;
use Application\Controller\RessourceRhControllerFactory;
use Application\Provider\Privilege\RessourceRhPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
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
                        'order' => 500,
                        'label' => 'Ressources',
                        'title' => "Ressources",
                        'route' => 'ressource-rh',
                        'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'ressource-rh' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/ressource-rh',
                    'defaults' => [
                        'controller' => RessourceRhController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'controllers'     => [
        'factories' => [
            RessourceRhController::class => RessourceRhControllerFactory::class,
        ],
    ],
];