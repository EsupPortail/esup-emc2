<?php

namespace Octopus;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Octopus\Controller\OctopusController;
use Octopus\Controller\OctopusControllerFactory;
use Octopus\Service\Structure\StructureService;
use Octopus\Service\Structure\StructureServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => OctopusController::class,
                    'action'     => [
                        'index',
                    ],
                    'roles' => [],
                ]
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_octopus' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Octopus\Entity\Db' => 'orm_octopus_xml_driver',
                ],
            ],
            'orm_octopus_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Octopus/Entity/Db/Mapping',
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
            'octopus' => [
                'type' => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/octopus',
                    'defaults' => [
                        'controller' => OctopusController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
//            'doctrine.connection.orm_octopus'    => new \DoctrineORMModule\Service\DBALConnectionFactory('orm_octopus'),
//            'doctrine.configuration.orm_octopus' => new \DoctrineORMModule\Service\ConfigurationFactory('orm_octopus'),
//            'doctrine.entitymanager.orm_octopus' => new \DoctrineORMModule\Service\EntityManagerFactory('orm_octopus'),
//            'doctrine.driver.orm_octopus'        => new \DoctrineModule\Service\DriverFactory('orm_octopus'),
//            'doctrine.eventmanager.orm_octopus'  => new \DoctrineModule\Service\EventManagerFactory('orm_octopus'),

            StructureService::class => StructureServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            OctopusController::class => OctopusControllerFactory::class,
        ]
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
