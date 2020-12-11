<?php

namespace Application;

use UnicaenNote\Controller\PorteNoteController;
use UnicaenNote\Controller\PorteNoteControllerFactory;
use UnicaenNote\Provider\Privilege\PortenotePrivileges;
use UnicaenNote\Service\PorteNote\PorteNoteService;
use UnicaenNote\Service\PorteNote\PorteNoteServiceFactory;
use UnicaenNote\View\Helper\PorteNoteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PorteNoteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        PortenotePrivileges::PORTENOTE_INDEX,

                    ],
                ],
                [
                    'controller' => PorteNoteController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        PortenotePrivileges::PORTENOTE_AFFICHER,

                    ],
                ],
                [
                    'controller' => PorteNoteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        PortenotePrivileges::PORTENOTE_AJOUTER,

                    ],
                ],
                [
                    'controller' => PorteNoteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        PortenotePrivileges::PORTENOTE_HISTORISER,

                    ],
                ],
                [
                    'controller' => PorteNoteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        PortenotePrivileges::PORTENOTE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaen-note' => [
                                'pages' => [
                                    'un-porte' => [
                                        'label' => 'Porte-note',
                                        'route' => 'unicaen-note/porte-note',
                                        'resource' => PrivilegeController::getResourceId(PorteNoteController::class, 'index'),
                                        'order'    => 10001,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unicaen-note' => [
                'child_routes' => [
                    'porte-note' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/porte-note',
                            'defaults' => [
                                'controller' => PorteNoteController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:porte-note',
                                    'defaults' => [
                                        'controller' => PorteNoteController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => PorteNoteController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:porte-note',
                                    'defaults' => [
                                        'controller' => PorteNoteController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:porte-note',
                                    'defaults' => [
                                        'controller' => PorteNoteController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:porte-note',
                                    'defaults' => [
                                        'controller' => PorteNoteController::class,
                                        'action' => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            PorteNoteService::class => PorteNoteServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            PorteNoteController::class => PorteNoteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ],
    'view_helpers'  => [
        'aliases' => [
        ],
        'factories' => [
        ],
        'invokables' => [
            'portenote' => PorteNoteViewHelper::class,
        ],
    ],

];