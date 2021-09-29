<?php

namespace Application;

use Application\Form\HasPeriode\HasPeriodeForm;
use Application\Form\HasPeriode\HasPeriodeFormFactory;
use Application\Form\HasPeriode\HasPeriodeHydrator;
use Application\Form\HasPeriode\HasPeriodeHydratorFactory;
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
            HasPeriodeForm::class => HasPeriodeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            HasPeriodeHydrator::class => HasPeriodeHydratorFactory::class,
        ],
    ]

];