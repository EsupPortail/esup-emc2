<?php

namespace UnicaenEtat;

use UnicaenEtat\Controller\ActionController;
use UnicaenEtat\Controller\ActionControllerFactory;
use UnicaenEtat\Form\Action\ActionForm;
use UnicaenEtat\Form\Action\ActionFormFactory;
use UnicaenEtat\Form\Action\ActionHydrator;
use UnicaenEtat\Form\Action\ActionHydratorFactory;
use UnicaenEtat\Provider\Privilege\EtatPrivileges;
use UnicaenEtat\Service\Action\ActionService;
use UnicaenEtat\Service\Action\ActionServiceFactory;
use UnicaenEtat\View\Helper\ActionBadgeViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ActionController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_INDEX,
                    ],
                ],
                [
                    'controller' => ActionController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_AJOUTER,
                    ],
                ],
                [
                    'controller' => ActionController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_MODIFIER,
                    ],
                ],
                [
                    'controller' => ActionController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_HISTORISER,
                    ],
                ],
                [
                    'controller' => ActionController::class,
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
                                    'action' => [
                                        'label' => 'Action type',
                                        'route' => 'unicaen-etat/action',
                                        'resource' => PrivilegeController::getResourceId(ActionController::class, 'index'),
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
                    'action' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/action',
                            'defaults' => [
                                'controller' => ActionController::class,
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
                                        'controller' => ActionController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:action',
                                    'defaults' => [
                                        'controller' => ActionController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:action',
                                    'defaults' => [
                                        'controller' => ActionController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:action',
                                    'defaults' => [
                                        'controller' => ActionController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:action',
                                    'defaults' => [
                                        'controller' => ActionController::class,
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
            ActionService::class => ActionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ActionController::class => ActionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ActionForm::class => ActionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ActionHydrator::class => ActionHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'actionbadge'  => ActionBadgeViewHelper::class,
        ],
    ],


];