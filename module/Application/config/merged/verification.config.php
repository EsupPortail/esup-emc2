<?php

namespace Application;

use Application\Controller\IndexController;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
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
                        AppRoleProvider::ADMIN_TECH,
                        AppRoleProvider::ADMIN_FONC,
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
                            'verification_menu' => [
                                'label'    => 'Vérification',
                                'route'    => 'verification',
                                'resource' => PrivilegeController::getResourceId(VerificationController::class, 'index') ,
                                'order'    => 999900,
                                'dropdown-header' => true,
                            ],
                            'verification' => [
                                'label'    => 'Vérification installation',
                                /** @see VerificationController::indexAction() */
                                'route'    => 'verification',
                                'resource' => PrivilegeController::getResourceId(VerificationController::class, 'index') ,
                                'order'    => 999998,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'phpinfo' => [
                                'label'    => 'Vérification php',
                                /** @see IndexController::infosAction() */
                                'route'    => 'infos',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'infos') ,
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