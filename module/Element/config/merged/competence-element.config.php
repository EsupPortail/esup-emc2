<?php

namespace Element;

use Element\Form\CompetenceElement\CompetenceElementForm;
use Element\Form\CompetenceElement\CompetenceElementFormFactory;
use Element\Form\CompetenceElement\CompetenceElementHydrator;
use Element\Form\CompetenceElement\CompetenceElementHydratorFactory;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceElement\CompetenceElementServiceFactory;
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
            CompetenceElementService::class => CompetenceElementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceElementForm::class => CompetenceElementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceElementHydrator::class => CompetenceElementHydratorFactory::class,
        ],
    ]

];