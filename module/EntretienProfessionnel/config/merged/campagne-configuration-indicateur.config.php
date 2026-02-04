<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\CampagneConfigurationIndicateurController;
use EntretienProfessionnel\Controller\CampagneConfigurationIndicateurControllerFactory;
use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurForm;
use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurFormFactory;
use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurHydrator;
use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\CampagnePrivileges;
use EntretienProfessionnel\Service\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurService;
use EntretienProfessionnel\Service\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CampagneConfigurationIndicateurController::class,
                    'action' => [
                        'index',
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_MODIFIER
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'campagne' => [
                        'child_routes' => [
                            'configuration-indicateur' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/configuration-indicateur',
                                    'defaults' => [
                                        /** @see CampagneConfigurationIndicateurController::indexAction() */
                                        'controller' => CampagneConfigurationIndicateurController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/ajouter',
                                            'defaults' => [
                                                /** @see CampagneConfigurationIndicateurController::ajouterAction() */
                                                'action' => 'ajouter',
                                            ],
                                        ],
                                    ],
                                    'modifier' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/modifier/:campagne-configuration-indicateur',
                                            'defaults' => [
                                                /** @see CampagneConfigurationIndicateurController::modifierAction() */
                                                'action' => 'modifier',
                                            ],
                                        ],
                                    ],
                                    'historiser' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/historiser/:campagne-configuration-indicateur',
                                            'defaults' => [
                                                /** @see CampagneConfigurationIndicateurController::historiserAction() */
                                                'action' => 'historiser',
                                            ],
                                        ],
                                    ],
                                    'restaurer' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/restaurer/:campagne-configuration-indicateur',
                                            'defaults' => [
                                                /** @see CampagneConfigurationIndicateurController::restaurerAction() */
                                                'action' => 'restaurer',
                                            ],
                                        ],
                                    ],
                                    'supprimer' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/supprimer/:campagne-configuration-indicateur',
                                            'defaults' => [
                                                /** @see CampagneConfigurationIndicateurController::supprimerAction() */
                                                'action' => 'supprimer',
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
            CampagneConfigurationIndicateurService::class => CampagneConfigurationIndicateurServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CampagneConfigurationIndicateurController::class => CampagneConfigurationIndicateurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CampagneConfigurationIndicateurForm::class => CampagneConfigurationIndicateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CampagneConfigurationIndicateurHydrator::class => CampagneConfigurationIndicateurHydratorFactory::class,
        ],
    ]

];