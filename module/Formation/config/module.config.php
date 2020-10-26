<?php

namespace Formation;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
//                [
//                    'controller' => IndexController::class,
//                    'action' => [
//                        'index-administration',
//                    ],
//                    'privileges' => [
//                        MailingPrivileges::MAILING_AFFICHER,
//                        IndexPrivileges::AFFICHER_INDEX,
//                        SynchroPrivileges::SYNCHRO_AFFICHER,
//                        UtilisateurPrivileges::UTILISATEUR_AFFICHER,
//                        RolePrivileges::ROLE_AFFICHER,
//                        ValidationPrivileges::AFFICHER,
//                        ValidationtypePrivileges::VALIDATIONTYPE_AFFICHER,
//                        PrivilegePrivileges::PRIVILEGE_VOIR,
//                        ConfigurationPrivileges::CONFIGURATION_AFFICHER,
//                    ],
//                ],
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Formation\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Formation/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__Formation__' . __NAMESPACE__,
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
//                    'gestion' => [
//                        'order' => 400,
//                        'label' => 'Gestion',
//                        'title' => "Gestion des fiches, entretiens et des affectations",
//                        'route' => 'gestion',
//                        'resource' => PrivilegeController::getResourceId(IndexController::class, 'index-gestion'),
//                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
//            'home'        => [
//                'type'          => Literal::class,
//                'may_terminate' => true,
//                'options' => [
//                    'route'    => '/',
//                    'defaults' => [
//                        'controller' => 'Application\Controller\Index', // <-- change here
//                        'action'     => 'index',
//                    ],
//                ],
//            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
        ]
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];
