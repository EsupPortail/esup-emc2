<?php

namespace Application;

use Application\Controller\Utilisateur\UtilisateurController;
use Application\Controller\Utilisateur\UtilisateurControllerFactory;
use Application\Provider\Privilege\UtilisateurPrivileges;
use Application\Service\User\UserService;
use Application\Service\User\UserServiceFactory;
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
