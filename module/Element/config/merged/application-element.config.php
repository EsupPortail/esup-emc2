<?php

namespace Element;

use Element\Form\ApplicationElement\ApplicationElementForm;
use Element\Form\ApplicationElement\ApplicationElementFormFactory;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\ApplicationElement\ApplicationElementServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
            ApplicationElementService::class => ApplicationElementServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
        ],
    ],
    'form_elements' => [
        'factories' => [
            ApplicationElementForm::class => ApplicationElementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];