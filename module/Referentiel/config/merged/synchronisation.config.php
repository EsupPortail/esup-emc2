<?php

namespace Carriere;

use Referentiel\Service\Synchronisation\SynchronisationService;
use Referentiel\Service\Synchronisation\SynchronisationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

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
            SynchronisationService::class => SynchronisationServiceFactory::class,
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