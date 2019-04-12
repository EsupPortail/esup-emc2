<?php

namespace Utilisateur;

use Utilisateur\Controller\UtilisateurController;
use Utilisateur\Controller\UtilisateurControllerFactory;
use Utilisateur\Provider\Privilege\UtilisateurPrivileges;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\Role\RoleServiceFactory;
use Utilisateur\Service\User\UserService;
use Utilisateur\Service\User\UserServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;


return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'index',
                        'rechercher-utilisateur',
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'rechercher-people',
                        'effacer',
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'changer-status',
                    ],
                    'privileges' => [
                        UtilisateurPrivileges::CHANGER_STATUS,
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

//    'navigation' => [
//        'default' => [
//            'home' => [
//                'pages' => [
//                    'administration' => [
//                        'pages' => [
//                            'utilisateur' => [
//                                'label' => 'Utilisateur',
//                                'route' => 'utilisateur-preecog',
//                                'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::AFFICHER),
//                                'order'    => 10,
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],

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
                    'rechercher-utilisateur' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'       => '/rechercher-utilisateur',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'rechercher-utilisateur',
                            ],
                        ],
                    ],
                    'rechercher-people' => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'       => '/rechercher-people',
                            'defaults'    => [
                                'controller' => UtilisateurController::class,
                                'action' => 'rechercher-people',
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
//    'translator'      => [
//        'locale'                    => 'fr_FR', // en_US
//        'translation_file_patterns' => [
//            [
//                'type'     => 'gettext',
//                'base_dir' => __DIR__ . '/../language',
//                'pattern'  => '%s.mo',
//            ],
//        ],
//    ],
    'controllers'     => [
        'factories' => [
            UtilisateurController::class => UtilisateurControllerFactory::class,
        ]
    ],
];
