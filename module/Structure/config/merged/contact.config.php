<?php

namespace Structure;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Structure\Controller\ContactController;
use Structure\Controller\ContactControllerFactory;
use Structure\Controller\ObservateurController;
use Structure\Controller\ObservateurControllerFactory;
use Structure\Form\Observateur\ObservateurForm;
use Structure\Form\Observateur\ObservateurFormFactory;
use Structure\Form\Observateur\ObservateurHydrator;
use Structure\Form\Observateur\ObservateurHydratorFactory;
use Structure\Provider\Privilege\StructureobservateurPrivileges;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Observateur\ObservateurServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_DESCRIPTION, //TODO privilege ...
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'structure' => [
                'child_routes' => [
                    'contact' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contact',
                            'defaults' => [
                                'controller' => ContactController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter/:structure',
                                    'defaults' => [
                                        /** @see ContactController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
//                            'modifier' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/modifier/:observateur[/:structure]',
//                                    'defaults' => [
//                                        /** @see ObservateurController::modifierAction() */
//                                        'action' => 'modifier',
//                                    ],
//                                ],
//                            ],
//                            'historiser' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/historiser/:observateur',
//                                    'defaults' => [
//                                        /** @see ObservateurController::historiserAction() */
//                                        'action' => 'historiser',
//                                    ],
//                                ],
//                            ],
//                            'restaurer' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/restaurer/:observateur',
//                                    'defaults' => [
//                                        /** @see ObservateurController::restaurerAction() */
//                                        'action' => 'restaurer',
//                                    ],
//                                ],
//                            ],
//                            'supprimer' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/supprimer/:observateur',
//                                    'defaults' => [
//                                        /** @see ObservateurController::supprimerAction() */
//                                        'action' => 'supprimer',
//                                    ],
//                                ],
//                            ],
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
            ContactController::class => ContactControllerFactory::class,
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