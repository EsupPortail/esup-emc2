<?php

namespace Application;

use Agent\Service\AgentStatut\AgentStatutService;
use Agent\Service\AgentStatut\AgentStatutServiceFactory;
use Agent\View\Helper\AgentStatutViewHelper;
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
            AgentStatutService::class => AgentStatutServiceFactory::class,
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
            'agentStatut' => AgentStatutViewHelper::class,
        ],
    ],

];