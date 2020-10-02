<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'UnicaenDocument\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/UnicaenDocument/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'UNICAEN-DOCUMENT__' . __NAMESPACE__,
            ],
        ],
    ],

    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'view_helpers'  => [
        'aliases' => [
        ],
        'factories' => [
        ],
    ],
];