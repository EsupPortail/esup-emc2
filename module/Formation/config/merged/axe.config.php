<?php

namespace Formation;

use Formation\Controller\AxeController;
use Formation\Controller\AxeControllerFactory;
use Formation\Form\Axe\AxeForm;
use Formation\Form\Axe\AxeFormFactory;
use Formation\Form\Axe\AxeHydrator;
use Formation\Form\Axe\AxeHydratorFactory;
use Formation\Provider\Privilege\AxePrivileges;
use Formation\Service\Axe\AxeService;
use Formation\Service\Axe\AxeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AxeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        AxePrivileges::AXE_INDEX,
                    ],
                ],
                [
                    'controller' => AxeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        AxePrivileges::AXE_AFFICHER,
                    ],
                ],
                [
                    'controller' => AxeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        AxePrivileges::AXE_AJOUTER,
                    ],
                ],
                [
                    'controller' => AxeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        AxePrivileges::AXE_MODIFIER,
                    ],
                ],
                [
                    'controller' => AxeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        AxePrivileges::AXE_HISTORISER,
                    ],
                ],
                [
                    'controller' => AxeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        AxePrivileges::AXE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'axe' => [
                                'label'    => 'Axe de formation',
                                'route'    => 'axe',
                                'resource' => PrivilegeController::getResourceId(AxeController::class, 'index') ,
                                'order'    => 205,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'axe' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/axe',
                    'defaults' => [
                        /** @see AxeController::indexAction() */
                        'controller' => AxeController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:axe',
                            'defaults' => [
                                /** @see AxeController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see AxeController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:axe',
                            'defaults' => [
                                /** @see AxeController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:axe',
                            'defaults' => [
                                /** @see AxeController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:axe',
                            'defaults' => [
                                /** @see AxeController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:axe',
                            'defaults' => [
                                /** @see AxeController::supprimerAction() */
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AxeService::class => AxeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AxeController::class => AxeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AxeForm::class => AxeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AxeHydrator::class => AxeHydratorFactory::class,
        ],
    ]

];