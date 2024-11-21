<?php

namespace FicheReferentiel;

use FicheReferentiel\Controller\ImporterController;
use FicheReferentiel\Controller\ImporterControllerFactory;
use FicheReferentiel\Service\Importer\ImporterService;
use FicheReferentiel\Service\Importer\ImporterServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ImporterController::class,
                    'action' => [
                        'importer-dgafp-csv',
                        'importer-referens3-csv',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-referentiel' => [
                'child_routes' => [
                    'importer-dgafp-csv' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/importer-dgafp-csv',
                            'defaults' => [
                                /** @see ImporterController::importerDgafpCsvAction() */
                                'controller' => ImporterController::class,
                                'action'     => 'importer-dgafp-csv',
                            ],
                        ],
                    ],
                    'importer-referens3-csv' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/importer-referens3-csv',
                            'defaults' => [
                                /** @see ImporterController::importerReferens3CsvAction() */
                                'controller' => ImporterController::class,
                                'action'     => 'importer-referens3-csv',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ImporterService::class => ImporterServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ImporterController::class => ImporterControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];