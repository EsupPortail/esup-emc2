<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Service\CampagneProgressionStructure\CampagneProgressionStructureService;
use EntretienProfessionnel\Service\CampagneProgressionStructure\CampagneProgressionStructureServiceFactory;
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
            CampagneProgressionStructureService::class => CampagneProgressionStructureServiceFactory::class,
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