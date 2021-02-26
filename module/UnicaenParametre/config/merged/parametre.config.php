<?php

namespace UnicaenParametre;

use UnicaenParametre\Form\Parametre\ParametreForm;
use UnicaenParametre\Form\Parametre\ParametreFormFactory;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenParametre\Service\Parametre\ParametreServiceFactory;
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
            ParametreService::class => ParametreServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [
            ParametreForm::class => ParametreFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ParametreService::class => ParametreServiceFactory::class,
        ],
    ]

];