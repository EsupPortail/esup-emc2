<?php

use UnicaenUtilisateur\Controller\UtilisateurController;
use UnicaenUtilisateur\Controller\UtilisateurControllerFactory;
use UnicaenUtilisateur\Form\User\UserForm;
use UnicaenUtilisateur\Form\User\UserFormFactory;
use UnicaenUtilisateur\Form\User\UserHydrator;
use UnicaenUtilisateur\Form\User\UserHydratorFactory;
use UnicaenUtilisateur\Provider\Privilege\UtilisateurPrivileges;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\Role\RoleServiceFactory;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenUtilisateur\Service\User\UserServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'index',
                        'rechercher',
                        'listing',
                        'export'
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::UTILISATEUR_AFFICHER,
                    ],
                ],
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'effacer',
                        'ajouter',
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::UTILISATEUR_AJOUTER,
                    ],
                ],
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'changer-status',
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::STATUT_CHANGER,
                    ],
                ],
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'add-role',
                        'remove-role',
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::MODIFIER_ROLE,
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
                            'utilisateur' => [
                                'label' => 'Utilisateur',
                                'route' => 'utilisateur-preecog',
                                'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AFFICHER),
                                'order'    => 1001,
                                'pages' => [
                                    'listing-utilisateur' => [
                                        'label' => 'Listing',
                                        'route' => 'utilisateur-preecog/listing',
                                        'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AFFICHER),
                                        'order'    => 10001,
                                    ],
                                    'ajouter-utilisateur' => [
                                        'label' => 'Listing',
                                        'route' => 'utilisateur-preecog/ajouter',
                                        'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AJOUTER),
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
            'utilisateur-preecog' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/utilisateur',
                    'defaults' => [
                        'controller' => UtilisateurController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'listing' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'       => '/listing',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'listing',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'       => '/ajouter',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'export' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'       => '/export',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'export',
                            ],
                        ],
                    ],
                    'rechercher' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/rechercher[/:service-name]',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'add-role' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/add-role/:utilisateur/:role',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'add-role',
                            ],
                        ],
                    ],
                    'remove-role' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/remove-role/:utilisateur/:role',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'remove-role',
                            ],
                        ],
                    ],
                    'changer-status' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/changer-status/:utilisateur',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'changer-status',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'          => Segment::class,
                        'options'       => [
                            'route'       => '/effacer/:utilisateur',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'effacer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            UserService::class => UserServiceFactory::class,
            RoleService::class => RoleServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            UserForm::class => UserFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            UserHydrator::class => UserHydratorFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            UtilisateurController::class => UtilisateurControllerFactory::class,
        ]
    ],
];
