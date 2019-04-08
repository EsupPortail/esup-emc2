<?php

namespace Autoform;

use Autoform\Form\Champ\ChampForm;
use Autoform\Form\Champ\ChampFormFactory;
use Autoform\Form\Champ\ChampHydrator;
use Autoform\Form\Champ\ChampHydratorFactory;
use Autoform\Service\Champ\ChampService;
use Autoform\Service\Champ\ChampServiceFactory;
use Autoform\View\Helper\ChampAsInputHelper;
use Autoform\View\Helper\ChampAsResultHelper;
use Autoform\View\Helper\ChampAsValidationHelper;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [],
        ],
    ],

    'router' => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            ChampService::class => ChampServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [],
    ],
    'form_elements' => [
        'factories' => [
            ChampForm::class => ChampFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ChampHydrator::class => ChampHydratorFactory::class,
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'champAsResult'              => ChampAsResultHelper::class,
            'champAsInput'               => ChampAsInputHelper::class,
            'champAsValidation'          => ChampAsValidationHelper::class,
        ],
    ],

];