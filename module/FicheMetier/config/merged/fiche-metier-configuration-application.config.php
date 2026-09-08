<?php

namespace FicheMetier;

use FicheMetier\Provider\Privilege\ConfigurationPrivileges;
use FicheMetier\Controller\FicheMetierConfigurationApplicationParDefautController;
use FicheMetier\Controller\FicheMetierConfigurationApplicationParDefautControllerFactory;
use FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut\FicheMetierConfigurationApplicationParDefautService;
use FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut\FicheMetierConfigurationApplicationParDefautServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierConfigurationApplicationParDefautController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationApplicationParDefautController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationApplicationParDefautController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_DETRUIRE,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationApplicationParDefautController::class,
                    'action' => [
                        'reappliquer',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_REAPPLIQUER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-metier' => [
                'child_routes' => [
                    'configuration' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/configuration',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'applications-par-defaut' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/applications-par-defaut',
                                    'defaults' => [
                                        /** @see FicheMetierConfigurationApplicationParDefautController::indexAction() */
                                        'controller' => FicheMetierConfigurationApplicationParDefautController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'ajouter' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/ajouter',
                                            'defaults' => [
                                                /** @see FicheMetierConfigurationApplicationParDefautController::ajouterAction() */
                                                'action' => 'ajouter',
                                            ],
                                        ],
                                    ],
                                    'supprimer' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/supprimer/:application-par-defaut',
                                            'defaults' => [
                                                /** @see FicheMetierConfigurationApplicationParDefautController::supprimerAction() */
                                                'action' => 'supprimer',
                                            ],
                                        ],
                                    ],
                                    'reappliquer' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/reappliquer',
                                            'defaults' => [
                                                /** @see FicheMetierConfigurationApplicationParDefautController::reappliquerAction() */
                                                'action' => 'reappliquer',
                                            ],
                                        ],
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
            FicheMetierConfigurationApplicationParDefautService::class => FicheMetierConfigurationApplicationParDefautServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FicheMetierConfigurationApplicationParDefautController::class => FicheMetierConfigurationApplicationParDefautControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ]

];