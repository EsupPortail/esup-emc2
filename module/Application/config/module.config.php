<?php

namespace Application;

use Application\Service\Affectation\AffectationService;
use Application\Service\Affectation\AffectationServiceFactory;
use Application\Service\MailService\MailService;
use Application\Service\MailService\MailServiceFactory;
use Application\Service\Role\RoleService;
use Application\Service\Role\RoleServiceFactory;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [],
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
        ],
    ],
    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            MailService::class => MailServiceFactory::class,
            RoleService::class => RoleServiceFactory::class,
            AffectationService::class => AffectationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Index' => Controller\IndexController::class,
        ],
        'factories' => [
        ]
    ],

    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
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
];
