<?php

namespace Application;

use Application\Controller\Immobilier\ImmobilierController;
use Application\Controller\Immobilier\ImmobilierControllerFactory;
use Application\Controller\Structure\StructureController;
use Application\Controller\Structure\StructureControllerFactory;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Provider\Privilege\StructurePrivileges;
use Application\Service\Immobilier\ImmobilierService;
use Application\Service\Immobilier\ImmobilierServiceFactory;
use Application\Service\Structure\StructureService;
use Application\Service\Structure\StructureServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

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