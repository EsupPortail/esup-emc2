<?php

use Autoform\Provider\Privilege\IndexPrivileges;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenPrivilege\Guard\PrivilegeController;

return array(
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [],
        ],
    ],
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Autoform\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Autoform/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__' . __NAMESPACE__,
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'autoform' => [
                                'label'    => 'Formulaires',
                                'route'    => 'autoform/formulaires',
                                'resource' => IndexPrivileges::getResourceId(IndexPrivileges::AFFICHER_INDEX),
                                'order'    => 90009,
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
            'autoform' => [
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
);
