<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'UnicaenPrivilege\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/UnicaenPrivilege/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'UNICAEN__' . __NAMESPACE__,
            ],
        ],
    ],


    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
