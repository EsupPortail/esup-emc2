<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Mailing\Controller\Mailing\MailingController;
use Mailing\Controller\Mailing\MailingControllerFactory;
use Mailing\Provider\Privilege\MailingPrivileges;
use Mailing\Service\Mailing\MailingService;
use Mailing\Service\Mailing\MailingServiceFactory;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

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
                        MailingPrivileges::HISTORIQUE,

                    ],
                ],
                [
                    'controller' => MailingController::class,
                    'action'     => [
                        'afficher',
                    ],
                    'privileges' => [
                        MailingPrivileges::AFFICHER,

                    ],
                ],
                [
                    'controller' => MailingController::class,
                    'action'     => [
                        'mail-test',
                    ],
                    'privileges' => [
                        MailingPrivileges::ENVOI_TEST,

                    ],
                ],
                [
                    'controller' => MailingController::class,
                    'action'     => [
                        're-envoi',
                    ],
                    'privileges' => [
                        MailingPrivileges::RE_ENVOI,

                    ],
                ],
                [
                    'controller' => MailingController::class,
                    'action'     => [
                        'effacer',
                    ],
                    'privileges' => [
                        MailingPrivileges::EFFACER,

                    ],
                ],
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Mailing\Model\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Mailing/Model/Db/Mapping',
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
                    'mail-test' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/mail-test',
                            'defaults' => [
                                'controller' => MailingController::class,
                                'action'     => 'mail-test',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => MailingController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/effacer/:id',
                            'defaults' => [
                                'controller' => MailingController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                    're-envoi' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/re-envoi/:id',
                            'defaults' => [
                                'controller' => MailingController::class,
                                'action'     => 're-envoi',
                            ],
                        ],
                    ],
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
            MailingService::class => MailingServiceFactory::class,
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
