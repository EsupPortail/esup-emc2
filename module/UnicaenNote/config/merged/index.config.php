<?php

namespace UnicaenNote;

use UnicaenNote\Controller\IndexController;
use UnicaenNote\Controller\IndexControllerFactory;
use UnicaenNote\Controller\NoteController;
use UnicaenNote\Provider\Privilege\NotePrivileges;
use UnicaenNote\Provider\Privilege\PortenotePrivileges;
use UnicaenNote\Provider\Privilege\TypePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        PortenotePrivileges::PORTENOTE_INDEX,
                        TypePrivileges::TYPE_INDEX,
                        NotePrivileges::NOTE_INDEX,

                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'unicaen-note' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/unicaen-note',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
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
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];