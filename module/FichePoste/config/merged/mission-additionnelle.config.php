<?php

namespace FichePoste;

use FichePoste\Service\MissionAdditionnelle\MissionAdditionnelleService;
use FichePoste\Service\MissionAdditionnelle\MissionAdditionnelleServiceFactory;
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
            MissionAdditionnelleService::class => MissionAdditionnelleServiceFactory::class
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