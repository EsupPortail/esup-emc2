<?php

namespace Application;

use Application\Constant\RoleConstant;
use Application\Controller\VerificationController;
use Application\Controller\VerificationControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => VerificationController::class,
                    'action' => [
                        'index'
                    ],
                    'roles' => [
                        RoleConstant::ADMIN_TECH,
                        RoleConstant::ADMIN_FONC,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'verification' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/verification',
                    'defaults' => [
                        'controller' => VerificationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'verification' => [
                                'label'    => 'Vérification installation',
                                'route'    => 'verification',
                                'resource' => PrivilegeController::getResourceId(VerificationController::class, 'index') ,
                                'order'    => 999999,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
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
            VerificationController::class => VerificationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];