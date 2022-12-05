<?php

namespace Carriere;

use Carriere\Controller\CorrespondanceController;
use Carriere\Controller\CorrespondanceControllerFactory;
use Carriere\Controller\CorrespondanceTypeController;
use Carriere\Controller\CorrespondanceTypeControllerFactory;
use Carriere\Provider\Privilege\CorrespondancePrivileges;
use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\Correspondance\CorrespondanceServiceFactory;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

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
                [
                    'controller' => CorrespondanceTypeController::class,
                    'action' => [
                        'index',
                        'afficher',
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
            'carriere' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/carriere',
                    'defaults' => [
                        'controller' => CorrespondanceController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'correspondance-type' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/correspondance-type',
                            'defaults' => [
                                'controller' => CorrespondanceTypeController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:type',
                                    'defaults' => [
                                        'controller' => CorrespondanceTypeController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
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
            CorrespondanceTypeService::class => CorrespondanceTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CorrespondanceController::class => CorrespondanceControllerFactory::class,
            CorrespondanceTypeController::class => CorrespondanceTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];