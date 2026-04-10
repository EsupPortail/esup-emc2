<?php

namespace Application;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentAffectation\AgentAffectationServiceFactory;
use Agent\View\Helper\AgentAffectationViewHelper;
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
            AgentAffectationService::class => AgentAffectationServiceFactory::class,
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
            'agentAffectation' => AgentAffectationViewHelper::class,
        ],
    ],

];