<?php

namespace FicheMetier;

use FicheMetier\Controller\FicheMetierConfigurationAffichageController;
use FicheMetier\Controller\FicheMetierConfigurationAffichageControllerFactory;
use FicheMetier\Provider\Privilege\ConfigurationPrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierConfigurationAffichageController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationAffichageController::class,
                    'action' => [
                        'toggle-parametre',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AJOUTER,
                        ConfigurationPrivileges::CONFIGURATION_DETRUIRE,
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
                            'affichage' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/affichage',
                                    'defaults' => [
                                        /** @see FicheMetierConfigurationAffichageController::indexAction() */
                                        'controller' => FicheMetierConfigurationAffichageController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'toggle-parametre' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/toggle-parametre[/:type/:parametre]',
                                            'defaults' => [
                                                /** @see FicheMetierConfigurationAffichageController::toggleParametreAction() */
                                                'controller' => FicheMetierConfigurationAffichageController::class,
                                                'action' => 'toggle-parametre',
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
        ],
    ],
    'controllers' => [
        'factories' => [
            FicheMetierConfigurationAffichageController::class => FicheMetierConfigurationAffichageControllerFactory::class,
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