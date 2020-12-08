<?php

namespace Application;

use UnicaenNote\Controller\TypeController;
use UnicaenNote\Controller\TypeControllerFactory;
use UnicaenNote\Form\Type\TypeForm;
use UnicaenNote\Form\Type\TypeFormFactory;
use UnicaenNote\Form\Type\TypeHydrator;
use UnicaenNote\Form\Type\TypeHydratorFactory;
use UnicaenNote\Provider\Privilege\TypePrivileges;
use UnicaenNote\Service\Type\TypeService;
use UnicaenNote\Service\Type\TypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        TypePrivileges::TYPE_INDEX,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        TypePrivileges::TYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        TypePrivileges::TYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        TypePrivileges::TYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        TypePrivileges::TYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        TypePrivileges::TYPE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unicaen-note' => [
                'child_routes' => [
                    'type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/type',
                            'defaults' => [
                                'controller' => TypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:type',
                                    'defaults' => [
                                        'controller' => TypeController::class,
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
                                        'controller' => TypeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:type',
                                    'defaults' => [
                                        'controller' => TypeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:type',
                                    'defaults' => [
                                        'controller' => TypeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:type',
                                    'defaults' => [
                                        'controller' => TypeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:type',
                                    'defaults' => [
                                        'controller' => TypeController::class,
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
            TypeService::class => TypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            TypeController::class => TypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            TypeForm::class => TypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            TypeHydrator::class => TypeHydratorFactory::class,
        ],
    ]

];