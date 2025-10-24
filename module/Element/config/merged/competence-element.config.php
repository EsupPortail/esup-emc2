<?php

namespace Element;

use Element\Form\CompetenceElement\CompetenceElementForm;
use Element\Form\CompetenceElement\CompetenceElementFormFactory;
use Element\Form\CompetenceElement\CompetenceElementHydrator;
use Element\Form\CompetenceElement\CompetenceElementHydratorFactory;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceElement\CompetenceElementServiceFactory;
use Element\View\Helper\ApplicationsViewHelper;
use Element\View\Helper\ApplicationsViewHelperFactory;
use Element\View\Helper\CompetenceDisciplineArrayViewHelper;
use Element\View\Helper\CompetenceDisciplineArrayViewHelperFactory;
use Element\View\Helper\CompetencesViewHelper;
use Element\View\Helper\CompetencesViewHelperFactory;
use Element\View\Helper\CompetenceTypeArrayViewHelper;
use Element\View\Helper\CompetenceTypeArrayViewHelperFactory;
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
    ],
    'view_helpers' => [
        'factories' => [
            CompetencesViewHelper::class => CompetencesViewHelperFactory::class,
            CompetenceTypeArrayViewHelper::class => CompetenceTypeArrayViewHelperFactory::class,
            CompetenceDisciplineArrayViewHelper::class => CompetenceDisciplineArrayViewHelperFactory::class,
        ],
        'aliases' => [
            'competences' => CompetencesViewHelper::class,
            'competenceDisciplineArray' => CompetenceDisciplineArrayViewHelper::class,
            'competenceTypeArray' => CompetenceTypeArrayViewHelper::class,
        ],
    ],
];