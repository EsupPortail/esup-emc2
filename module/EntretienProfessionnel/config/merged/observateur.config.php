<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\ObservateurController;
use EntretienProfessionnel\Controller\ObservateurControllerFactory;
use EntretienProfessionnel\Form\ImporterObservateur\ImporterObservateurForm;
use EntretienProfessionnel\Form\ImporterObservateur\ImporterObservateurFormFactory;
use EntretienProfessionnel\Form\ImporterObservateur\ImporterObservateurHydrator;
use EntretienProfessionnel\Form\ImporterObservateur\ImporterObservateurHydratorFactory;
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
                        'index-observateur',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_INDEX_OBSERVATEUR,
                    ],
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'ajouter',
                        'importer',
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
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'rechercher',
                    ],
                    'privileges' => [
                        ObservateurPrivileges::OBSERVATEUR_RECHERCHER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'entretienpros' => [
                                'label' => 'Gestion des entretiens professionnels',
                                'route' => 'entretien-professionnel',
                                'resource' =>  EntretienproPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_INDEX) ,
                                'order'    => 3000,
                                'dropdown-header' => true,
                            ],
                            'observateur' => [
                                'label' => 'Observateurs',
                                'route' => 'entretien-professionnel/observateur',
                                'resource' =>  ObservateurPrivileges::getResourceId(ObservateurPrivileges::OBSERVATEUR_INDEX) ,
                                'order'    => 3030,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
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
                            'index-observateur' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/index',
                                    'defaults' => [
                                        /** @see ObservateurController::indexObservateurAction() */
                                        'action'     => 'index-observateur',
                                    ],
                                ],
                            ],
                            'importer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/importer[/:campagne]',
                                    'defaults' => [
                                        /** @see ObservateurController::importerAction() */
                                        'action'     => 'importer',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter[/:entretien-professionnel]',
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
                            'rechercher' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route'    => '/rechercher',
                                    'defaults' => [
                                        /** @see ObservateurController::rechercherAction() */
                                        'action'     => 'rechercher',
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
            ImporterObservateurForm::class => ImporterObservateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ObservateurHydrator::class => ObservateurHydratorFactory::class,
            ImporterObservateurHydrator::class => ImporterObservateurHydratorFactory::class,
        ],
    ]

];