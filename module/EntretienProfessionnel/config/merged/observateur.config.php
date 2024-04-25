<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\EntretienProfessionnelController;
use EntretienProfessionnel\Controller\ObservateurController;
use EntretienProfessionnel\Controller\ObservateurControllerFactory;
use EntretienProfessionnel\Form\Observateur\ObservateurForm;
use EntretienProfessionnel\Form\Observateur\ObservateurFormFactory;
use EntretienProfessionnel\Form\Observateur\ObservateurHydrator;
use EntretienProfessionnel\Form\Observateur\ObservateurHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Provider\Privilege\ObservateurPrivileges;
use EntretienProfessionnel\Service\Observateur\ObservateurService;
use EntretienProfessionnel\Service\Observateur\ObservateurServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_INDEX,
                    ],
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_AJOUTER,
                    ],
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_MODIFIER,
                    ],
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_HISTORISER,
                    ],
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'observateur' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/observateur',
                            'defaults' => [
                                /** @see ObservateurController::indexAction() */
                                'controller' => ObservateurController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:entretien-professionnel',
                                    'defaults' => [
                                        /** @see ObservateurController::ajouterAction() */
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::modifierAction() */
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::historiserAction() */
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::restaurerAction() */
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::supprimerAction() */
                                        'action'     => 'supprimer',
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
            ObservateurService::class => ObservateurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ObservateurController::class => ObservateurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ObservateurForm::class => ObservateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ObservateurHydrator::class => ObservateurHydratorFactory::class,
        ],
    ]

];