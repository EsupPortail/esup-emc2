<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Driver\OCI8\Driver as OCI8;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Mailing\Controller\Mailing\MailingController;
use Mailing\Controller\Mailing\MailingControllerFactory;
use Mailing\Provider\Privilege\MailingPrivileges;
use Mailing\Service\MailingService;
use Zend\Mvc\Router\Http\Literal;

return array(
    'bjyauthorize'    => [
        'guards' => [
            \UnicaenAuth\Guard\PrivilegeController::class => [
                [
                    'controller' => MailingController::class,
                    'action'     => [
                        'index',
                    ],
                    'privileges' => [
                        MailingPrivileges::AFFICHER,

                    ],
                ],
            ],
        ],
    ],
    'doctrine'     => [
        'driver'     => [
            'orm_default'        => [
                'class'   => MappingDriverChain::class,
                'drivers' => [
                    'Indicateur\Model' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Indicateur/Model/Db/Mapping',
                ],
            ],
        ],
        'connection'    => [
            'orm_default' => [
                'driver_class' => OCI8::class,
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'mailing' => [
                        'label'    => 'Mailing',
                        'route'    => 'mailing',
                        'resource' => MailingPrivileges::getResourceId(MailingPrivileges::AFFICHER),
                        'order'    => 1,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'mailing' => [
                'type' => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/mailing',
                    'defaults' => [
                        'controller' => MailingController::class,
                        'action'     => 'index',
                    ],
                ],
                'child_routes'  => [
                ],
            ],
        ],
    ],

    'form_elements' => [
        'factories' => [
//            IndicateurForm::class => IndicateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
//            IndicateurHydrator::class => IndicateurHydrator::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            MailingService::class => \Application\Service\MailingServiceFactory::class,
        ],

    ],
    'controllers' => [
        'factories' => [
            MailingController::class => MailingControllerFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
//            'completIndicateurThese'    => CompletIndicateurTheseHelper::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
);
