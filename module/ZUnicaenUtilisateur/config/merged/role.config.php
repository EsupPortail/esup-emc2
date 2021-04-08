<?php

use UnicaenUtilisateur\Controller\RoleController;
use UnicaenUtilisateur\Controller\RoleControllerFactory;
use UnicaenUtilisateur\Form\Role\RoleForm;
use UnicaenUtilisateur\Form\Role\RoleFormFactory;
use UnicaenUtilisateur\Form\Role\RoleHydrator;
use UnicaenUtilisateur\Form\Role\RoleHydratorFactory;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\Role\RoleServiceFactory;
use UnicaenUtilisateur\Provider\Privilege\RolePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RoleController::class,
                    'action' => [
                        'index',
                        'listing',
                    ],
                    'privileges' => [
                        RolePrivileges::ROLE_AFFICHER,
                    ],
                ],
                [
                    'controller' => RoleController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                    ],
                    'privileges' => [
                        RolePrivileges::ROLE_MODIFIER,
                    ],
                ],
                [
                    'controller' => RoleController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        RolePrivileges::ROLE_EFFACER,
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
                            'role' => [
                                'label' => 'Rôle',
                                'route' => 'role',
                                'resource' => RolePrivileges::getResourceId(RolePrivileges::ROLE_AFFICHER),
                                'order'    => 1002,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'role' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/role',
                    'defaults' => [
                        'controller' => RoleController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'listing' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/listing/:role',
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action' => 'listing',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'       => '/ajouter',
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/modifier/:role',
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/supprimer/:role',
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            RoleService::class => RoleServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RoleController::class => RoleControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            RoleForm::class => RoleFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            RoleHydrator::class => RoleHydratorFactory::class,
        ],
    ],
];
