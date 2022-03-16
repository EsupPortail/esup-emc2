<?php

namespace Carriere;

use Carriere\Controller\CorrespondanceController;
use Carriere\Controller\CorrespondanceControllerFactory;
use Carriere\Provider\Privilege\CorrespondancePrivileges;
use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\Correspondance\CorrespondanceServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CorrespondanceController::class,
                    'action' => [
                        'index',
                        'afficher-agents',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_INDEX,
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
                        'pages' => [
                            [
                                'order' => 825,
                                'label' => 'Correspondances',
                                'route' => 'correspondance',
                                'resource' => PrivilegeController::getResourceId(CorrespondanceController::class, 'index') ,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'correspondance' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/correspondance',
                    'defaults' => [
                        'controller' => CorrespondanceController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agents/:correspondance',
                            'defaults' => [
                                'controller' => CorrespondanceController::class,
                                'action'     => 'afficher-agents',
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
            CorrespondanceService::class => CorrespondanceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CorrespondanceController::class => CorrespondanceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];