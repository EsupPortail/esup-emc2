<?php

namespace FichePoste;

use FichePoste\Service\Notification\NotificationService;
use FichePoste\Service\Notification\NotificationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [

            ],
        ],
    ],

    'router'          => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            NotificationService::class => NotificationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];