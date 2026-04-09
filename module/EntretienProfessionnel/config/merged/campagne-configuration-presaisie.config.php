<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\CampagneConfigurationPresaisieController;
use EntretienProfessionnel\Controller\CampagneConfigurationPresaisieControllerFactory;
use EntretienProfessionnel\Form\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieForm;
use EntretienProfessionnel\Form\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieFormFactory;
use EntretienProfessionnel\Form\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieHydrator;
use EntretienProfessionnel\Form\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\CampagnePrivileges;
use EntretienProfessionnel\Service\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieService;
use EntretienProfessionnel\Service\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CampagneConfigurationPresaisieController::class,
                    'action' => [
                        'index',
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
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
                            'configuration-presaisie' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/configuration-presaisie',
                                    'defaults' => [
                                        /** @see CampagneConfigurationPresaisieController::indexAction() */
                                        'controller' => CampagneConfigurationPresaisieController::class,
                                        'action' => 'index',
                                    ],
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/ajouter',
                                            'defaults' => [
                                                /** @see CampagneConfigurationPresaisieController::ajouterAction() */
                                                'action' => 'ajouter',
                                            ],
                                        ],
                                    ],
                                    'modifier' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/modifier/:campagne-configuration-presaisie',
                                            'defaults' => [
                                                /** @see CampagneConfigurationPresaisieController::modifierAction() */
                                                'action' => 'modifier',
                                            ],
                                        ],
                                    ],
                                    'historiser' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/historiser/:campagne-configuration-presaisie',
                                            'defaults' => [
                                                /** @see CampagneConfigurationPresaisieController::historiserAction() */
                                                'action' => 'historiser',
                                            ],
                                        ],
                                    ],
                                    'restaurer' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/restaurer/:campagne-configuration-presaisie',
                                            'defaults' => [
                                                /** @see CampagneConfigurationPresaisieController::restaurerAction() */
                                                'action' => 'restaurer',
                                            ],
                                        ],
                                    ],
                                    'supprimer' => [
                                        'type' => Segment::class,
                                        'options' => [
                                            'route' => '/supprimer/:campagne-configuration-presaisie',
                                            'defaults' => [
                                                /** @see CampagneConfigurationPresaisieController::supprimerAction() */
                                                'action' => 'supprimer',
                                            ],
                                        ],
                                    ],
                                    'verifier' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => '/verifier',
                                            'defaults' => [
                                                /** @see CampagneConfigurationPresaisieController::verifierAction() */
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
            CampagneConfigurationPresaisieService::class => CampagneConfigurationPresaisieServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CampagneConfigurationPresaisieController::class => CampagneConfigurationPresaisieControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CampagneConfigurationPresaisieForm::class => CampagneConfigurationPresaisieFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CampagneConfigurationPresaisieHydrator::class => CampagneConfigurationPresaisieHydratorFactory::class,
        ],
    ]

];