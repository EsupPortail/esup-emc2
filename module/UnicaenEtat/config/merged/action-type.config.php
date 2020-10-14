<?php

namespace UnicaenEtat;

use UnicaenEtat\View\Helper\ActionTypeViewHelper;
use UnicaenEtat\Controller\ActionTypeController;
use UnicaenEtat\Controller\ActionTypeControllerFactory;
use UnicaenEtat\Form\ActionType\ActionTypeForm;
use UnicaenEtat\Form\ActionType\ActionTypeFormFactory;
use UnicaenEtat\Form\ActionType\ActionTypeHydrator;
use UnicaenEtat\Form\ActionType\ActionTypeHydratorFactory;
use UnicaenEtat\Provider\Privilege\EtatPrivileges;
use UnicaenEtat\Service\ActionType\ActionTypeService;
use UnicaenEtat\Service\ActionType\ActionTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ActionTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_INDEX,
                    ],
                ],
                [
                    'controller' => ActionTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_AJOUTER,
                    ],
                ],
                [
                    'controller' => ActionTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_MODIFIER,
                    ],
                ],
                [
                    'controller' => ActionTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_HISTORISER,
                    ],
                ],
                [
                    'controller' => ActionTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_DETRUIRE,
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
                            'unicaen-etat' => [
                                'pages' => [
                                    'action-type' => [
                                        'label' => 'Action type',
                                        'route' => 'unicaen-etat/action-type',
                                        'resource' => PrivilegeController::getResourceId(ActionTypeController::class, 'index'),
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
            'unicaen-etat' => [
                'child_routes' => [
                    'action-type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/action-type',
                            'defaults' => [
                                'controller' => ActionTypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => ActionTypeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:action-type',
                                    'defaults' => [
                                        'controller' => ActionTypeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:action-type',
                                    'defaults' => [
                                        'controller' => ActionTypeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:action-type',
                                    'defaults' => [
                                        'controller' => ActionTypeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:action-type',
                                    'defaults' => [
                                        'controller' => ActionTypeController::class,
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
            ActionTypeService::class => ActionTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ActionTypeController::class => ActionTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ActionTypeForm::class => ActionTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ActionTypeHydrator::class => ActionTypeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'etattype'  => ActionTypeViewHelper::class,
        ],
    ],


];