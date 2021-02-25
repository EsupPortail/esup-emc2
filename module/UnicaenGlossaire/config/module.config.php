<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'UnicaenGlossaire\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/UnicaenGlossaire/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'UNICAEN-GLOSSAIRE__' . __NAMESPACE__,
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

    'public_files' => [
        'inline_scripts' => [
//            '202_' => 'vendor/chart-2.9.3/Chart.bundle.min.js',
        ],
        'stylesheets' => [
            '10000_' => 'css/unicaen-glossaire.css',
        ],
    ],
];