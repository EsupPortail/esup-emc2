<?php

namespace Application;

use Application\Controller\PosteController;
use Application\Controller\PosteControllerFactory;
use Application\Form\Poste\PosteForm;
use Application\Form\Poste\PosteFormFactory;
use Application\Form\Poste\PosteHydrator;
use Application\Form\Poste\PosteHydratorFactory;
use Application\Provider\Privilege\PostePrivileges;
use Application\Service\Poste\PosteService;
use Application\Service\Poste\PosteServiceFactory;
use Application\View\Helper\PosteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        PostePrivileges::POSTE_INDEX,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'afficher',
                        'rechercher-batiment',
                    ],
                    'privileges' => [
                        PostePrivileges::POSTE_AFFICHER,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        PostePrivileges::POSTE_AJOUTER,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        PostePrivileges::POSTE_EDITER,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        PostePrivileges::POSTE_EFFACER,
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
                            [
                                'order' => 1200,
                                'label' => 'Postes',
                                'route' => 'poste',
                                'resource' => PrivilegeController::getResourceId(PosteController::class, 'index') ,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'poste' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/poste',
                    'defaults' => [
                        'controller' => PosteController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:poste',
                            'defaults' => [
                                'controller' => PosteController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:poste',
                            'defaults' => [
                                'controller' => PosteController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => PosteController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:poste',
                            'defaults' => [
                                'controller' => PosteController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            PosteService::class => PosteServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            PosteController::class => PosteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            PosteForm::class => PosteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            PosteHydrator::class => PosteHydratorFactory::class,
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'poste' => PosteViewHelper::class,
        ],
    ],

];