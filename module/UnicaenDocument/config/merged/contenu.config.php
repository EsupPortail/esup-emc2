<?php

use UnicaenDocument\Controller\ContenuController;
use UnicaenDocument\Controller\ContenuControllerFactory;
use UnicaenDocument\Form\Contenu\ContenuForm;
use UnicaenDocument\Form\Contenu\ContenuFormFactory;
use UnicaenDocument\Form\Contenu\ContenuHydrator;
use UnicaenDocument\Form\Contenu\ContenuHydratorFactory;
use UnicaenDocument\Provider\Privilege\DocumentcontentPrivileges;
use UnicaenDocument\Service\Contenu\ContenuService;
use UnicaenDocument\Service\Contenu\ContenuServiceFactory;
use UnicaenDocument\Service\Exporter\ExporterService;
use UnicaenDocument\Service\Exporter\ExporterServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_INDEX,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_AJOUTER,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_MODIFIER,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_HISTORISER,
                    ],
                ],
                [
                    'controller' => ContenuController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        DocumentcontentPrivileges::DOCUMENTCONTENU_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'contenu' => [
                                'pages' => [
                                    'contenu' => [
                                        'label' => 'Contenu',
                                        'route' => 'contenu/contenu',
                                        'resource' => PrivilegeController::getResourceId(ContenuController::class, 'index'),
                                        'order'    => 10001,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'contenu' => [
                'child_routes' => [
                    'contenu' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contenu',
                            'defaults' => [
                                'controller' => ContenuController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:contenu',
                                    'defaults' => [
                                        'controller' => ContenuController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => ContenuController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:contenu',
                                    'defaults' => [
                                        'controller' => ContenuController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:contenu',
                                    'defaults' => [
                                        'controller' => ContenuController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:contenu',
                                    'defaults' => [
                                        'controller' => ContenuController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:contenu',
                                    'defaults' => [
                                        'controller' => ContenuController::class,
                                        'action' => 'detruire',
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
            ContenuService::class => ContenuServiceFactory::class,
            ExporterService::class => ExporterServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ContenuForm::class => ContenuFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ContenuHydrator::class => ContenuHydratorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ContenuController::class => ContenuControllerFactory::class,
        ]
    ],
];
