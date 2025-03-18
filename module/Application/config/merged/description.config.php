<?php

namespace Application;

use Application\Form\HasDescription\HasDescriptionForm;
use Application\Form\HasDescription\HasDescriptionFormFactory;
use Application\Form\HasDescription\HasDescriptionHydrator;
use Application\Form\HasDescription\HasDescriptionHydratorFactory;
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
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [
            HasDescriptionForm::class => HasDescriptionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            HasDescriptionHydrator::class => HasDescriptionHydratorFactory::class,
        ],
    ]

];