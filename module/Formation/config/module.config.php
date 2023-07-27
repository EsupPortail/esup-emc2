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
use Formation\Service\Evenement\NotificationFormationsOuvertesService;
use Formation\Service\Evenement\NotificationFormationsOuvertesServiceFactory;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use Formation\Service\Evenement\RappelAgentAvantFormationServiceFactory;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Notification\NotificationServiceFactory;
use Formation\Service\Url\UrlService;
use Formation\Service\Url\UrlServiceFactory;
use Unicaen\Console\Console;
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
                        'apropos',
                        'index',
                        'contact',
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
                        'may_terminate' => true,
                    ],
                    'apropos' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/mes-formations-apropos',
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action'     => 'apropos',
                            ],
                        ],
                    ],
                    'contact' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/mes-formations-contact',
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action'     => 'contact',
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
                    'apropos'                  => [
                        'label'    => _("À propos"),
                        'title'    => _("À propos de cette application"),
                        'route'    => 'mes-formations/apropos',
                        'class'    => 'apropos',
                        'visible'  => false,
                        'footer'   => true, // propriété maison pour inclure cette page dans le menu de pied de page
                        'sitemap'  => true, // propriété maison pour inclure cette page dans le plan
                        'order'    => 1001,
                    ],
                    'contact'                  => [
                        'label'    => _("Contact"),
                        'title'    => _("Contact concernant l'application"),
                        'route'    => 'contact',
                        'class'    => 'contact',
                        'visible'  => false,
                        'footer'   => true, // propriété maison pour inclure cette page dans le menu de pied de page
                        'sitemap'  => true, // propriété maison pour inclure cette page dans le plan
                        'resource' => 'controller/UnicaenApp\Controller\Application:contact',
                        'order'    => 1002,
                    ],
//                    'plan'                     => [
//                        'label'    => _("Plan de navigation"),
//                        'title'    => _("Plan de navigation au sein de l'application"),
//                        'route'    => 'plan',
//                        'class'    => 'plan',
//                        'visible'  => false,
//                        'footer'   => true, // propriété maison pour inclure cette page dans le menu de pied de page
//                        'sitemap'  => true, // propriété maison pour inclure cette page dans le plan
//                        'resource' => 'controller/UnicaenApp\Controller\Application:plan',
//                        'order'    => 1003,
//                    ],
                    'mentions-legales'         => [
                        'label'    => _("Mentions légales"),
                        'title'    => _("Mentions légales"),
                        'uri'      => 'http://www.unicaen.fr/acces-direct/mentions-legales/',
                        'class'    => 'ml',
                        'visible'  => false,
                        'footer'   => true, // propriété maison pour inclure cette page dans le menu de pied de page
                        'sitemap'  => true, // propriété maison pour inclure cette page dans le plan
                        'order'    => 1004,
                    ],
                    'informatique-et-libertes' => [
                        'label'    => _("Vie privée"),
                        'title'    => _("Vie privée"),
                        'uri'      => 'http://www.unicaen.fr/acces-direct/vie-privee/',
                        'class'    => 'il',
                        'visible'  => false,
                        'footer'   => true, // propriété maison pour inclure cette page dans le menu de pied de page
                        'sitemap'  => true, // propriété maison pour inclure cette page dans le plan
                        'order'    => 1005,
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
            'error/403'     => realpath('./module/Formation/view/error/403.phtml'),
            'error/404'     => realpath('./module/Formation/view/error/404.phtml'),
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
