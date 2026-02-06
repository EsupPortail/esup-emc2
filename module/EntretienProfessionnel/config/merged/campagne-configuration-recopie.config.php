<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\CampagneConfigurationRecopieController;
use EntretienProfessionnel\Controller\CampagneConfigurationRecopieControllerFactory;
use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieForm;
use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieFormFactory;
use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieHydrator;
use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\CampagnePrivileges;
use EntretienProfessionnel\Service\CampagneConfigurationRecopie\CampagneConfigurationRecopieService;
use EntretienProfessionnel\Service\CampagneConfigurationRecopie\CampagneConfigurationRecopieServiceFactory;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CampagneConfigurationRecopieController::class,
                    'action' => [
                        'index',
                        'ajouter',
//                        'modifier',
//                        'historiser',
//                        'restaurer',
//                        'supprimer',
                        'verifier',
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
                            'configuration-recopie' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/configuration-recopie',
                                    'defaults' => [
                                        /** @see CampagneConfigurationRecopieController::indexAction() */
                                        'controller' => CampagneConfigurationRecopieController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/ajouter',
                                            'defaults' => [
                                                /** @see CampagneConfigurationRecopieController::ajouterAction() */
                                                'action' => 'ajouter',
                                            ],
                                        ],
                                    ],
//                                    'modifier' => [
//                                        'type' => Segment::class,
//                                        'options' => [
//                                            'route' => '/modifier/:campagne-configuration-indicateur',
//                                            'defaults' => [
//                                                /** @see CampagneConfigurationIndicateurController::modifierAction() */
//                                                'action' => 'modifier',
//                                            ],
//                                        ],
//                                    ],
//                                    'historiser' => [
//                                        'type' => Segment::class,
//                                        'options' => [
//                                            'route' => '/historiser/:campagne-configuration-indicateur',
//                                            'defaults' => [
//                                                /** @see CampagneConfigurationIndicateurController::historiserAction() */
//                                                'action' => 'historiser',
//                                            ],
//                                        ],
//                                    ],
//                                    'restaurer' => [
//                                        'type' => Segment::class,
//                                        'options' => [
//                                            'route' => '/restaurer/:campagne-configuration-indicateur',
//                                            'defaults' => [
//                                                /** @see CampagneConfigurationIndicateurController::restaurerAction() */
//                                                'action' => 'restaurer',
//                                            ],
//                                        ],
//                                    ],
//                                    'supprimer' => [
//                                        'type' => Segment::class,
//                                        'options' => [
//                                            'route' => '/supprimer/:campagne-configuration-indicateur',
//                                            'defaults' => [
//                                                /** @see CampagneConfigurationIndicateurController::supprimerAction() */
//                                                'action' => 'supprimer',
//                                            ],
//                                        ],
//                                    ],
                                    'verifier' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/verifier',
                                            'defaults' => [
                                                /** @see CampagneConfigurationRecopieController::verifierAction() */
                                                'action' => 'verifier',
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
            CampagneConfigurationRecopieService::class => CampagneConfigurationRecopieServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CampagneConfigurationRecopieController::class => CampagneConfigurationRecopieControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CampagneConfigurationRecopieForm::class => CampagneConfigurationRecopieFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CampagneConfigurationRecopieHydrator::class => CampagneConfigurationRecopieHydratorFactory::class,
        ],
    ]

];