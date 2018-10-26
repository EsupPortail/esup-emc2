<?php

namespace Application;

use Application\Controller\FicheMetier\FicheMetierController;
use Application\Controller\FicheMetier\FicheMetierControllerFactory;
use Application\Controller\Utilisateur\UtilisateurController;
use Application\Controller\Utilisateur\UtilisateurControllerFactory;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetier\FicheMetierServiceFactory;
use Application\Service\MailService\MailService;
use Application\Service\MailService\MailServiceFactory;
use Application\Service\Role\RoleService;
use Application\Service\Role\RoleServiceFactory;
use Application\Service\User\UserService;
use Application\Service\User\UserServiceFactory;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::HISTORISER,
                    ],
                ],
                [
                    'controller' => UtilisateurController::class,
                    'action' => [
                        'index',
                        'rechercher-utilisateur',
                        'rechercher-people',
                        'add-role',
                        'remove-role',
                        'changer-status',
                        'effacer'
                    ],
                    'roles' => [
                    ],
                ],
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Application\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Application/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__' . __NAMESPACE__,
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'home'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                    'defaults' => [
                        'controller' => FicheMetierController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                ],
            ],
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
        'invokables' => [
        ],
        'factories' => [
            FicheMetierService::class => FicheMetierServiceFactory::class,
            MailService::class => MailServiceFactory::class,
            RoleService::class => RoleServiceFactory::class,
            UserService::class => UserServiceFactory::class,
        ],
    ],
    'translator'      => [
        'locale'                    => 'fr_FR', // en_US
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Index' => Controller\IndexController::class,
        ],
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
            UtilisateurController::class => UtilisateurControllerFactory::class,
        ]
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
