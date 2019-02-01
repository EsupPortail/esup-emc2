<?php

namespace Application;

use Application\Controller\Poste\PosteController;
use Application\Controller\Poste\PosteControllerFactory;
use Application\Form\Poste\PosteForm;
use Application\Form\Poste\PosteFormFactory;
use Application\Form\Poste\PosteHydrator;
use Application\Form\Poste\PosteHydratorFactory;
use Application\Provider\Privilege\PostePrivileges;
use Application\Service\Poste\PosteService;
use Application\Service\Poste\PosteServiceFactory;
use Application\View\Helper\PosteViewHelper;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'rechercher-batiment',
                    ],
                    'privileges' => [
                        PostePrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        PostePrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        PostePrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => PosteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        PostePrivileges::EFFACER,
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
                    'rechercher-batiment' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-batiment',
                            'defaults' => [
                                'controller' => PosteController::class,
                                'action'     => 'rechercher-batiment',
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