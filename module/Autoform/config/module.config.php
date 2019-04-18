<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenAuth\Guard\PrivilegeController;

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
