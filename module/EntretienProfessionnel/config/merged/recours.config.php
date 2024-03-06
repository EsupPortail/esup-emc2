<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\RecoursController;
use EntretienProfessionnel\Controller\RecoursControllerFactory;
use EntretienProfessionnel\Form\Recours\RecoursForm;
use EntretienProfessionnel\Form\Recours\RecoursFormFactory;
use EntretienProfessionnel\Form\Recours\RecoursHydrator;
use EntretienProfessionnel\Form\Recours\RecoursHydratorFactory;
use EntretienProfessionnel\Service\Recours\RecoursService;
use EntretienProfessionnel\Service\Recours\RecoursServiceFactory;
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
            RecoursService::class => RecoursServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RecoursController::class => RecoursControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            RecoursForm::class => RecoursFormFactory::class
        ],
    ],
    'hydrators' => [
        'factories' => [
            RecoursHydrator::class => RecoursHydratorFactory::class,
        ],
    ]

];