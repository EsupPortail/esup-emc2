<?php

use UnicaenPrivilege\Controller\PrivilegeController;
use UnicaenPrivilege\Controller\PrivilegeControllerFactory;
use UnicaenPrivilege\Provider\Privilege\PrivilegePrivileges;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProviderFactory;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [

    'bjyauthorize' => [
        'guards' => [
            UnicaenPrivilege\Guard\PrivilegeController::class => [
                [
                    'controller' => PrivilegeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_VOIR,
                    ],
                ],
                [
                    'controller' => PrivilegeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        PrivilegePrivileges::PRIVILEGE_AFFECTER,
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'invokables' => [],
        'factories' => [
            PrivilegeController::class  => PrivilegeControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            PrivilegeService::class => PrivilegeServiceFactory::class,
            PrivilegeRuleProvider::class => PrivilegeRuleProviderFactory::class, //ne sert pas ...
        ],
    ],

    'router' => [
        'routes' => [
            'privilege' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/privilege',
                    'defaults' => [
                        'controller' => PrivilegeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                ],
            ],
            'modifier-privilege' => [
                'type'          => Segment::class,
                'options'       => [
                    'route'    => '/modifier-privilege/:role/:privilege',
                    'defaults' => [
                        'controller'    => PrivilegeController::class,
                        'action'        => 'modifier',
                    ],
                ],
            ],

        ],
    ],

//    'navigation' => [
//        'default' => [
//            'home' => [
//                'pages' => [
//                    'administration' => [
//                        'pages' => [
//                            'privilege' => [
//                                'label' => 'Privilège',
//                                'route' => 'privilege',
//                                'resource' => \UnicaenPrivilege\Guard\PrivilegeController::getResourceId(PrivilegeController::class, 'index'),
//                                'order'    => 5000,
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],
];