<?php

namespace Element;

use Element\Controller\CompetenceImporterController;
use Element\Controller\CompetenceImporterControllerFactory;
use Element\Form\CompetenceImportation\CompetenceImportationForm;
use Element\Form\CompetenceImportation\CompetenceImportationFormFactory;
use Element\Form\CompetenceImportation\CompetenceImportationHydrator;
use Element\Form\CompetenceImportation\CompetenceImportationHydratorFactory;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceImporterController::class,
                    'action' => [
                        'importer',
                    ],
                    'role' => [
                        'Administrateur·trice technique',
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'competence-import' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/competence-import',
                    'defaults' => [
                        /** @see CompetenceImporterController::importerAction() */
                        'controller' => CompetenceImporterController::class,
                        'action' => 'importer',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers' => [
        'factories' => [
            CompetenceImporterController::class => CompetenceImporterControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceImportationForm::class => CompetenceImportationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceImportationHydrator::class => CompetenceImportationHydratorFactory::class,
        ],
    ]

];