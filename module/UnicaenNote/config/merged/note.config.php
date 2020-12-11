<?php

namespace Application;

use UnicaenNote\Controller\NoteController;
use UnicaenNote\Controller\NoteControllerFactory;
use UnicaenNote\Form\Note\NoteForm;
use UnicaenNote\Form\Note\NoteFormFactory;
use UnicaenNote\Form\Note\NoteHydrator;
use UnicaenNote\Form\Note\NoteHydratorFactory;
use UnicaenNote\Provider\Privilege\NotePrivileges;
use UnicaenNote\Service\Note\NoteService;
use UnicaenNote\Service\Note\NoteServiceFactory;
use UnicaenNote\View\Helper\NoteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NoteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        NotePrivileges::NOTE_INDEX,

                    ],
                ],
                [
                    'controller' => NoteController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        NotePrivileges::NOTE_AFFICHER,

                    ],
                ],
                [
                    'controller' => NoteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        NotePrivileges::NOTE_AJOUTER,

                    ],
                ],
                [
                    'controller' => NoteController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        NotePrivileges::NOTE_MODIFIER,

                    ],
                ],
                [
                    'controller' => NoteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        NotePrivileges::NOTE_HISTORISER,

                    ],
                ],
                [
                    'controller' => NoteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        NotePrivileges::NOTE_SUPPRIMER,

                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unicaen-note' => [
                'child_routes' => [
                    'note' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/note',
                            'defaults' => [
                                'controller' => NoteController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:note',
                                    'defaults' => [
                                        'controller' => NoteController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:porte-note]',
                                    'defaults' => [
                                        'controller' => NoteController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:note',
                                    'defaults' => [
                                        'controller' => NoteController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:note',
                                    'defaults' => [
                                        'controller' => NoteController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:note',
                                    'defaults' => [
                                        'controller' => NoteController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:note',
                                    'defaults' => [
                                        'controller' => NoteController::class,
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
            NoteService::class => NoteServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            NoteController::class => NoteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            NoteForm::class => NoteFormFactory::class
        ],
    ],
    'hydrators' => [
        'factories' => [
            NoteHydrator::class => NoteHydratorFactory::class,
        ],
    ],
    'view_helpers'  => [
        'aliases' => [
        ],
        'factories' => [
        ],
        'invokables' => [
            'note' => NoteViewHelper::class,
        ],
    ],

];