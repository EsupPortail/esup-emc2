<?php

namespace Application;

use Application\Controller\IndexController;
use Application\Event\RgpdRenderer\RgpdRendererEvenement;
use Application\Event\RgpdRenderer\RgpdRendererEvenementFactory;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\ModifierLibelle\ModifierLibelleFormFactory;
use Application\Form\ModifierLibelle\ModifierLibelleHydrator;
use Application\Provider\Privilege\ActivitePrivileges;
use Application\Provider\Privilege\AdministrationPrivileges;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\ConfigurationPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Provider\Privilege\MissionspecifiqueaffectationPrivileges;
use Application\Provider\Privilege\MissionspecifiquePrivileges;
use Application\Provider\Privilege\PostePrivileges;
use Application\Provider\Privilege\SynchroPrivileges;
use Application\Provider\Privilege\ValidationPrivileges;
use Application\Service\Evenement\SynchroOctopusService;
use Application\Service\Evenement\SynchroOctopusServiceFactory;
use Application\Service\Notification\NotificationService;
use Application\Service\Notification\NotificationServiceFactory;
use Application\Service\Url\UrlService;
use Application\Service\Url\UrlServiceFactory;
use Application\View\Helper\ActionIconViewHelper;
use Application\View\Helper\SynchorniserIconViewHelper;
use Carriere\Provider\Privilege\CorpsPrivileges;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use Formation\Provider\Privilege\FormationPrivileges;
use Metier\Provider\Privilege\MetierPrivileges;
use Structure\Provider\Privilege\StructurePrivileges;
use UnicaenAutoform\Provider\Privilege\IndexPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Privilege\PrivilegePrivileges;
use UnicaenUtilisateur\Provider\Privilege\RolePrivileges;
use UnicaenUtilisateur\Provider\Privilege\UtilisateurPrivileges;
use UnicaenValidation\Provider\Privilege\ValidationtypePrivileges;
use Laminas\Router\Http\Literal;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index-administration',
                    ],
                    'privileges' => [
                        IndexPrivileges::AFFICHER_INDEX,
                        SynchroPrivileges::SYNCHRO_AFFICHER,
                        UtilisateurPrivileges::UTILISATEUR_AFFICHER,
                        RolePrivileges::ROLE_AFFICHER,
                        ValidationPrivileges::AFFICHER,
                        ValidationtypePrivileges::VALIDATIONTYPE_AFFICHER,
                        PrivilegePrivileges::PRIVILEGE_VOIR,
                        ConfigurationPrivileges::CONFIGURATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index-ressources',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_INDEX,
                        CorpsPrivileges::CORPS_INDEX,
                        MetierPrivileges::METIER_INDEX,
                        FormationPrivileges::FORMATION_ACCES,
                        ActivitePrivileges::ACTIVITE_AFFICHER,
                        ActivitePrivileges::ACTIVITE_INDEX,
                        MissionspecifiquePrivileges::MISSIONSPECIFIQUE_INDEX,
                        PostePrivileges::POSTE_INDEX,
                        StructurePrivileges::STRUCTURE_INDEX,
                    ],
                ],
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index-gestion',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_INDEX,
                        FicheMetierPrivileges::FICHEMETIER_INDEX,
                        FichePostePrivileges::FICHEPOSTE_INDEX,
                        MissionspecifiqueaffectationPrivileges::MISSIONSPECIFIQUEAFFECTATION_INDEX,
                    ],
                ],
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index-superieur',
                        'index-autorite',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_AFFICHER,
                    ],
                ],
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'infos',
                    ],
                    'privileges' => [
                        AdministrationPrivileges::ADMINISTRATION_AFFICHER,
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

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'etab'                     => [
                        'footer'   => false, // propriété maison pour inclure cette page dans le menu de pied de page
                    ],
                    'gestion' => [
                        'order' => 400,
                        'label' => 'Gestion',
                        'title' => "Gestion des fiches, entretiens et des affectations",
                        'route' => 'gestion',
                        'resource' => PrivilegeController::getResourceId(IndexController::class, 'index-gestion'),
                    ],
                    'ressource' => [
                        'order' => 500,
                        'label' => 'Ressources',
                        'title' => "Ressources",
                        'route' => 'ressource-rh',
                        'resource' => PrivilegeController::getResourceId(IndexController::class, 'index-ressources') ,
                    ],
                    'administration' => [
                        'order' => 10000,
                        'label' => 'Administration',
                        'title' => "Administration",
                        'route' => 'administration',
                        'resource' => PrivilegeController::getResourceId(IndexController::class, 'index-administration') ,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'home'        => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index',
                    ],
                ],
            ],
            'index-personnel' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/index-personnel',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-personnel',
                    ],
                ],
            ],
            'index-validateur' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/index-validateur',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-validateur',
                    ],
                ],
            ],
            'index-superieur' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/index-superieur',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-superieur',
                    ],
                ],
            ],
            'index-autorite' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/index-autorite',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-autorite',
                    ],
                ],
            ],
            'infos' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/infos',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'infos',
                    ],
                ],
            ],
            'gestion'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/gestion',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-gestion',
                    ],
                ],
                'may_terminate' => true,
            ],
            'ressource-rh'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/ressource-rh',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-ressources',
                    ],
                ],
                'may_terminate' => true,
            ],
            'administration'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'index-administration',
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
//            RoleService::class => RoleServiceFactory::class,
            NotificationService::class => NotificationServiceFactory::class,
            UrlService::class => UrlServiceFactory::class,

            RgpdRendererEvenement::class => RgpdRendererEvenementFactory::class,
            SynchroOctopusService::class => SynchroOctopusServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
        ],
        'factories' => [
            'Application\Controller\Index' => Controller\IndexControllerFactory::class,
        ]
    ],

    'form_elements' => [
        'factories' => [
            ModifierLibelleForm::class => ModifierLibelleFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            ModifierLibelleHydrator::class => ModifierLibelleHydrator::class,
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'actionIcon' => ActionIconViewHelper::class,
            'synchroniserIcon' => SynchorniserIconViewHelper::class,
        ],
    ],

    'view_manager'    => [
        'template_map'             => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ],
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

    'public_files' => [
        'inline_scripts' => [
            '110_' => 'vendor/DataTables-1.10.18/datatables.min.js',
            '114_' => 'vendor/bootstrap-select-1.13.2/dist/js/bootstrap-select.min.js',
            '150_' => 'js/tinymce/js/tinymce/tinymce.js',
            '151_' => 'js/form_fiche.js',
            '201_' => 'vendor/chart-2.9.3/Chart.bundle.js',
        ],
        'stylesheets' => [
            '050_bootstrap-theme' => '',
            '110_' => 'vendor/DataTables-1.10.18/datatables.min.css',
            '112_' => 'vendor/font-awesome-5.15.2/css/all.min.css',
            '114_' => 'vendor/bootstrap-select-1.13.2/dist/css/bootstrap-select.min.css',
            '990_' => 'css/ubuntu.css',
            '991_' => 'css/raleway.css',
            '060_unicaen' => 'css/unicaen.css',
            '996_' => 'css/icon.css',
            '997_' => 'css/highlight.css',
            '998_' => 'css/tooltip.css',
            '070_app' => 'css/app.css',
        ],
    ],
];
