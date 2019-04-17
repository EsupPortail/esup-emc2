<?php

namespace Application;

use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Fichier\FichierServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [],
        ],
    ],

    'router'          => [
        'routes' => [],
    ],

    'service_manager' => [
        'factories' => [
            FichierService::class => FichierServiceFactory::class,
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