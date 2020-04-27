<?php

namespace Application;

use Application\Controller\RessourceRhController;
use Application\Controller\RessourceRhControllerFactory;
use Application\Provider\Privilege\MetierPrivileges;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\RessourceRh\RessourceRhServiceFactory;
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
                        'get-grades-json',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'cartographie',
                        'export-cartographie',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_CARTOGRAPHIE,
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
                'child_routes' => [
                    'cartographie' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/cartographie',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'cartographie',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'export' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route'    => '/export',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'export-cartographie',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            RessourceRhService::class => RessourceRhServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RessourceRhController::class => RessourceRhControllerFactory::class,
        ],
    ],
];