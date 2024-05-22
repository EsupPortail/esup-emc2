<?php

namespace Structure;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Structure\Controller\ObservateurController;
use Structure\Controller\ObservateurControllerFactory;
use Structure\Form\Observateur\ObservateurForm;
use Structure\Form\Observateur\ObservateurFormFactory;
use Structure\Form\Observateur\ObservateurHydrator;
use Structure\Form\Observateur\ObservateurHydratorFactory;
use Structure\Provider\Privilege\StructureobservateurPrivileges;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Observateur\ObservateurServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_INDEX,
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'index-observateur',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_INDEXOBSERVATEUR,
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_AFFICHER,
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_AJOUTER,
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_MODIFIER,
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_HISTORISER,
                ],
                [
                    'controller' => ObservateurController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => StructureobservateurPrivileges::STRUCTUREOBSERVATEUR_SUPPRIMER,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'structure' => [
                'child_routes' => [
                    'observateur' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/observateur',
                            'defaults' => [
                                /** @see ObservateurController::indexAction() */
                                'controller' => ObservateurController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'index-observateur' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/index-observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::indexObservateurAction() */
                                        'action' => 'index-observateur',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:structure]',
                                    'defaults' => [
                                        /** @see ObservateurController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:observateur',
                                    'defaults' => [
                                        /** @see ObservateurController::supprimerAction() */
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

    'service_manager' => [
        'factories' => [
            ObservateurService::class => ObservateurServiceFactory::class,
        ],
    ],
    'controllers' => [
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