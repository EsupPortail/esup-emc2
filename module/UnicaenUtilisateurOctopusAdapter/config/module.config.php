<?php

use UnicaenUtilisateurOctopusAdapter\Service\OctopusService;
use UnicaenUtilisateurOctopusAdapter\Service\OctopusServiceFactory;

return [
    'service_manager' => [
        'factories' => [
            OctopusService::class => OctopusServiceFactory::class
        ],
    ],
];