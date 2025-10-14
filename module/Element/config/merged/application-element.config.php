<?php

namespace Element;

use Element\Form\ApplicationElement\ApplicationElementForm;
use Element\Form\ApplicationElement\ApplicationElementFormFactory;
use Element\Form\ApplicationElement\ApplicationElementHydrator;
use Element\Form\ApplicationElement\ApplicationElementHydratorFactory;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\ApplicationElement\ApplicationElementServiceFactory;
use Element\View\Helper\ApplicationsViewHelper;
use Element\View\Helper\ApplicationsViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [

            ],
        ],
    ],

    'router' => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            ApplicationElementService::class => ApplicationElementServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
        ],
    ],
    'form_elements' => [
        'factories' => [
            ApplicationElementForm::class => ApplicationElementFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ApplicationElementHydrator::class => ApplicationElementHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            ApplicationsViewHelper::class => ApplicationsViewHelperFactory::class,
        ],
        'aliases' => [
            'applications' => ApplicationsViewHelper::class,
        ],
    ],
];