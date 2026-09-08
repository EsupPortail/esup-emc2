<?php

namespace FicheMetier;

use FicheMetier\Provider\Privilege\ConfigurationPrivileges;
use FicheMetier\Controller\FicheMetierConfigurationCompetenceParDefautController;
use FicheMetier\Controller\FicheMetierConfigurationCompetenceParDefautControllerFactory;
use FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut\FicheMetierConfigurationCompetenceParDefautService;
use FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut\FicheMetierConfigurationCompetenceParDefautServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierConfigurationCompetenceParDefautController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationCompetenceParDefautController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationCompetenceParDefautController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_DETRUIRE,
                    ],
                ],
                [
                    'controller' => FicheMetierConfigurationCompetenceParDefautController::class,
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
                        'child_routes' => [
                            'competences-par-defaut' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/competences-par-defaut',
                                    'defaults' => [
                                        /** @see FicheMetierConfigurationCompetenceParDefautController::indexAction() */
                                        'controller' => FicheMetierConfigurationCompetenceParDefautController::class,
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
                                                /** @see FicheMetierConfigurationCompetenceParDefautController::ajouterAction() */
                                                'action' => 'ajouter',
                                            ],
                                        ],
                                    ],
                                    'supprimer' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/supprimer/:competence-par-defaut',
                                            'defaults' => [
                                                /** @see FicheMetierConfigurationCompetenceParDefautController::supprimerAction() */
                                                'action' => 'supprimer',
                                            ],
                                        ],
                                    ],
                                    'reappliquer' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/reappliquer',
                                            'defaults' => [
                                                /** @see FicheMetierConfigurationCompetenceParDefautController::reappliquerAction() */
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
            FicheMetierConfigurationCompetenceParDefautService::class => FicheMetierConfigurationCompetenceParDefautServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FicheMetierConfigurationCompetenceParDefautController::class => FicheMetierConfigurationCompetenceParDefautControllerFactory::class,
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