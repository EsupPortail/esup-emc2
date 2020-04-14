<?php

namespace Application;

use Application\Controller\GestionController;
use Application\Controller\GestionControllerFactory;
use Application\Provider\Privilege\AdministrationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => GestionController::class,
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

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'order' => 500,
                        'label' => 'Gestion',
                        'title' => "Gestion des fiches, entretiens et des affectations",
                        'route' => 'gestion',
                        'resource' => AdministrationPrivileges::getResourceId(AdministrationPrivileges::AFFICHER)
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'gestion' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/gestion',
                    'defaults' => [
                        'controller' => GestionController::class,
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
            GestionController::class => GestionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];