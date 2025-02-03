<?php

namespace UnicaeContact;

use Laminas\Router\Http\Literal;
use UnicaenContact\Controller\IndexController;
use UnicaenContact\Controller\IndexControllerFactory;
use UnicaenContact\Provider\Privilege\ContactPrivileges;
use UnicaenContact\Provider\Privilege\ContacttypePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ContactPrivileges::CONTACT_INDEX,
                        ContacttypePrivileges::CONTACTYPE_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unicaen-contact' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/unicaen-contact',
                    'defaults' => [
                        /** @see IndexController::indexAction(); */
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [],
    ],

];