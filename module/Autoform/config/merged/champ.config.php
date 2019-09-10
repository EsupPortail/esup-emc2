<?php

namespace Autoform;

use Autoform\Form\Champ\ChampForm;
use Autoform\Form\Champ\ChampFormFactory;
use Autoform\Form\Champ\ChampHydrator;
use Autoform\Form\Champ\ChampHydratorFactory;
use Autoform\Service\Champ\ChampService;
use Autoform\Service\Champ\ChampServiceFactory;
use Autoform\View\Helper\ChampAsInputHelperFactory;
use Autoform\View\Helper\ChampAsResultHelper;
use Autoform\View\Helper\ChampAsResultHelperFactory;
use Autoform\View\Helper\ChampAsValidationHelper;
use Autoform\View\Helper\InstanceAsDivHelper;
use Autoform\View\Helper\InstanceAsFormulaireHelper;
use Autoform\View\Helper\InstanceAsTextHelper;
use Autoform\View\Helper\ValidationAsTextHelper;
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
            //'champAsResult'              => ChampAsResultHelper::class,
            //'champAsInput'               => ChampAsInputHelper::class,
            'champAsValidation'          => ChampAsValidationHelper::class,
            'instanceAsText'             => InstanceAsTextHelper::class,
            'instanceAsDiv'              => InstanceAsDivHelper::class,
            'validationAsText'            => ValidationAsTextHelper::class,
            'instanceAsFormulaire'       => InstanceAsFormulaireHelper::class,
        ],
        'factories' => [
            'champAsInput'              => ChampAsInputHelperFactory::class,
            'champAsResult'             => ChampAsResultHelperFactory::class,
        ],
    ],

];