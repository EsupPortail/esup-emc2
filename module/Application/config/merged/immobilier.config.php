<?php

namespace Application;

use Application\Controller\ImmobilierController;
use Application\Controller\ImmobilierControllerFactory;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Service\Immobilier\ImmobilierService;
use Application\Service\Immobilier\ImmobilierServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ImmobilierController::class,
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

    'router'          => [
        'routes' => [
            'immobilier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/immobilier',
                    'defaults' => [
                        'controller' => ImmobilierController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ImmobilierService::class => ImmobilierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ImmobilierController::class => ImmobilierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];