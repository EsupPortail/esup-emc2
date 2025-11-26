<?php

namespace Element;

use CompetenceSynonyme\CompetenceSynonymeHydratorFactory;
use Element\Controller\ApplicationController;
use Element\Controller\CompetenceSynonymeController;
use Element\Controller\CompetenceSynonymeControllerFactory;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeForm;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeFormFactory;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeHydrator;
use Element\Provider\Privilege\CompetencePrivileges;
use Element\Service\CompetenceSynonyme\CompetenceSynonymeService;
use Element\Service\CompetenceSynonyme\CompetenceSynonymeServiceFactory;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceSynonymeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceSynonymeController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'supprimer',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'competence' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence',
                        ],
                        'child_routes' => [
                            'importer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/importer[/:referentiel]',
                                    'defaults' => [
                                        /** @see CompetenceController::importerAction() */
                                        'controller' => CompetenceController::class,
                                        'action' => 'importer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CompetenceSynonymeService::class => CompetenceSynonymeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CompetenceSynonymeController::class => CompetenceSynonymeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceSynonymeForm::class => CompetenceSynonymeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceSynonymeHydrator::class => CompetenceSynonymeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
        ],
        'aliases' => [
        ],
    ],
];