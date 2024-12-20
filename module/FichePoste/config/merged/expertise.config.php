<?php

namespace FichePoste;

use FichePoste\Form\Expertise\ExpertiseForm;
use FichePoste\Form\Expertise\ExpertiseFormFactory;
use FichePoste\Form\Expertise\ExpertiseHydrator;
use FichePoste\Form\Expertise\ExpertiseHydratorFactory;
use FichePoste\Service\Expertise\ExpertiseService;
use FichePoste\Service\Expertise\ExpertiseServiceFactory;
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
            ExpertiseService::class => ExpertiseServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [
            ExpertiseForm::class => ExpertiseFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ExpertiseHydrator::class => ExpertiseHydratorFactory::class,
        ],
    ]

];