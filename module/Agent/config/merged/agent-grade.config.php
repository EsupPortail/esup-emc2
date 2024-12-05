<?php

namespace Application;

use Agent\Service\AgentGrade\AgentGradeService;
use Agent\Service\AgentGrade\AgentGradeServiceFactory;
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
            AgentGradeService::class => AgentGradeServiceFactory::class,
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
    ]

];