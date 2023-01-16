<?php

namespace Formation;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Formation\Controller\IndexController;
use Formation\Controller\IndexControllerFactory;
use Formation\Event\Convocation\ConvocationEvent;
use Formation\Event\Convocation\ConvocationEventFactory;
use Formation\Event\DemandeRetour\DemandeRetourEvent;
use Formation\Event\DemandeRetour\DemandeRetourEventFactory;
use Formation\Event\InscriptionCloture\InscriptionClotureEvent;
use Formation\Event\InscriptionCloture\InscriptionClotureEventFactory;
use Formation\Event\SessionCloture\SessionClotureEvent;
use Formation\Event\SessionCloture\SessionClotureEventFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Service\Evenement\NotificationFormationsOuvertesService;
use Formation\Service\Evenement\NotificationFormationsOuvertesServiceFactory;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use Formation\Service\Evenement\RappelAgentAvantFormationServiceFactory;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Notification\NotificationServiceFactory;
use Formation\Service\Url\UrlService;
use Formation\Service\Url\UrlServiceFactory;
use Laminas\Console\Console;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;


switch(getenv('APPLICATION_ENV')) {
    case 'development':
        $hostname = 'mes-formations.n302z-dsi008.png.unicaen.fr:8443';
        break;
    case 'test':
        $hostname = 'mes-formations-pp.unicaen.fr';
        break;
    case 'production':
    default:
        $hostname = 'mes-formations.unicaen.fr';
        break;
}


return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'Formation\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/Formation/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__Formation__' . __NAMESPACE__,
            ],
        ],
    ],

    'formation' => [
        'mail' => [
            /**
             * Adresses des redirections si do_not_send est à true
             */
            'redirect_to' => ['jean-philippe.metivier@unicaen.fr', ],
            'do_not_send' => true,

            'subject_prefix' => 'Mes formations (test)',
            'from_name' => 'mes-formations.unicaen.fr | Application de gestion des formations du personnel',
            'from_email' => 'assistance@unicaen.fr',
        ],
    ],

    'router' => [
        'routes' => [
            'mes-formations' => [
                'type' => 'Hostname',
                'options' => [
                    'route' => ':hostname',
                    'constraints' => [
                        'hostname' => 'mes-formations(-pp)?(.n302z-dsi008)?.unicaen.fr',
                    ],
                    'defaults' => [
                        'hostname' => !Console::isConsole() ? $hostname : '',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'home' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/',
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'label' => _("Accueil"),
                'route' => 'home',
                'pages' => [
                ],
            ],
        ],
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'formation' => [
                                'label'    => 'Gestion des formations',
                                'route'    => 'formation',
                                'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_ACCES),
                                'order'    => 300,
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            NotificationService::class => NotificationServiceFactory::class,
            UrlService::class => UrlServiceFactory::class,

            //Evenement
            NotificationFormationsOuvertesService::class => NotificationFormationsOuvertesServiceFactory::class,
            RappelAgentAvantFormationService::class => RappelAgentAvantFormationServiceFactory::class,
            InscriptionClotureEvent::class => InscriptionClotureEventFactory::class,
            ConvocationEvent::class => ConvocationEventFactory::class,
            DemandeRetourEvent::class => DemandeRetourEventFactory::class,
            SessionClotureEvent::class => SessionClotureEventFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
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
        'template_map' => [
            'mes-formations/layout' => realpath('./module/Formation/view/layout/layout.phtml'),
        ],
    ],

    'public_files' => [
        'head_scripts'   => [
        ],
        'inline_scripts' => [
        ],
        'stylesheets' => [
            '090_appcss'                  => 'css/mes-formations.css',
        ],
    ],
];
