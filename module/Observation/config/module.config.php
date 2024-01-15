<?php

namespace Observation;

use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Observation\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Observation/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__Observation__' . __NAMESPACE__,
            ],
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
