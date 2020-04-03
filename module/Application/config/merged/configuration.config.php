<?php

namespace Application;

use Application\Controller\ConfigurationController;
use Application\Controller\ConfigurationControllerFactory;
use Application\Form\ConfigurationEntretienProfessionnel\ConfigurationEntretienProfessionnelForm;
use Application\Form\ConfigurationEntretienProfessionnel\ConfigurationEntretienProfessionnelFormFactory;
use Application\Form\ConfigurationEntretienProfessionnel\ConfigurationEntretienProfessionnelHydrator;
use Application\Form\ConfigurationEntretienProfessionnel\ConfigurationEntretienProfessionnelHydratorFactory;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierForm;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierFormFactory;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierHydrator;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierHydratorFactory;
use Application\Provider\Privilege\ConfigurationPrivileges;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\Configuration\ConfigurationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'ajouter-configuration-fiche-metier',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'detruire-configuration-fiche-metier',
                        'ajouter-recopie',
                        'modifier-recopie',
                        'supprimer-recopie',
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_DETRUIRE
                    ],
                ],
                [
                    'controller' => ConfigurationController::class,
                    'action' => [
                        'reappliquer-configuration-fiche-metier'
                    ],
                    'privileges' => [
                        ConfigurationPrivileges::CONFIGURATION_REAPPLIQUER
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'configuration' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/configuration',
                    'defaults' => [
                        'controller' => ConfigurationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter-configuration-fiche-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-configuration-fiche-metier/:type',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'ajouter-configuration-fiche-metier',
                            ],
                        ],
                    ],
                    'detruire-configuration-fiche-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-configuration-fiche-metier/:configuration',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'detruire-configuration-fiche-metier',
                            ],
                        ],
                    ],
                    'reappliquer-configuration-fiche-metier' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/reappliquer-configuration-fiche-metier',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'reappliquer-configuration-fiche-metier',
                            ],
                        ],
                    ],
                    /** configuration des entretien pro ***************************************************************/
                    'ajouter-recopie' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter-recopie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'ajouter-recopie',
                            ],
                        ],
                    ],
                    'modifier-recopie' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-recopie/:recopie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'modifier-recopie',
                            ],
                        ],
                    ],
                    'supprimer-recopie' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-recopie/:recopie',
                            'defaults' => [
                                'controller' => ConfigurationController::class,
                                'action'     => 'supprimer-recopie',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'configuration' => [
                                'label'    => 'Configuration',
                                'route'    => 'configuration',
                                'resource' => ConfigurationPrivileges::getResourceId(ConfigurationPrivileges::CONFIGURATION_AFFICHER),
                                'order'    => 213456,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ConfigurationService::class => ConfigurationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ConfigurationController::class => ConfigurationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ConfigurationFicheMetierForm::class => ConfigurationFicheMetierFormFactory::class,
            ConfigurationEntretienProfessionnelForm::class => ConfigurationEntretienProfessionnelFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ConfigurationFicheMetierHydrator::class => ConfigurationFicheMetierHydratorFactory::class,
            ConfigurationEntretienProfessionnelHydrator::class => ConfigurationEntretienProfessionnelHydratorFactory::class,
        ],
    ]
];
