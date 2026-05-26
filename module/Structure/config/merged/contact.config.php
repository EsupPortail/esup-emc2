<?php

namespace Structure;

use Agent\Controller\AgentMobiliteController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Structure\Controller\ContactController;
use Structure\Controller\ContactControllerFactory;
use Structure\Provider\Privilege\StructurePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_INDEX, //TODO privilege ...
                ],
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
                        'may_terminate' => true,
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


    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'contact' => [
                                'label' => 'Gestion des contacts',
                                'route' => 'structure/contact',
                                'resource' => PrivilegeController::getResourceId(ContactController::class, 'index'),
                                'order' => 100001,
                                'icon' => 'fas fa-angle-right',
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