<?php

namespace Application;

use Agent\Service\AgentEmploiType\AgentEmploiTypeService;
use Agent\Service\AgentEmploiType\AgentEmploiTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [

            ],
        ],
    ],

    'router' => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentEmploiTypeService::class => AgentEmploiTypeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ]

];