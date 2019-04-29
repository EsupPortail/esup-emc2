<?php

namespace Application;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use UnicaenAuth\Guard\PrivilegeController;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\Role\RoleServiceFactory;
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
            'administration'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index', // <-- change here
                        'action'     => 'administration',
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
            RoleService::class => RoleServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
        ],
        'factories' => [
            'Application\Controller\Index' => Controller\IndexControllerFactory::class,
        ]
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
            '100_' => 'js/jquery.ui.datepicker-fr.js',
//            '110_' => 'https://gest.unicaen.fr/public/bootstrap-select-1.9.4/dist/js/bootstrap-select.min.js',
            '111_' => 'https://gest.unicaen.fr/public/bootstrap-confirmation-2.4.0/bootstrap-confirmation.min.js',
            '112_' => 'vendor/font-awesome-5.0.9/fontawesome-all.min.js',
            '120_' => 'vendor/bootstrap-select-1.13.2/dist/js/bootstrap-select.min.js',
            '124_' => 'vendor/vakata-jstree-3.3.4/dist/jstree.min.js',
            '150_' => 'js/tinymce/js/tinymce/tinymce.js',
            '151_' => 'js/form_fiche.js',
        ],
        'stylesheets' => [
            '050_bootstrap-theme' => '',
//            '111_' => 'https://gest.unicaen.fr/public/open-sans-gh-pages/open-sans.css',
//            '113_' => 'https://gest.unicaen.fr/public/bootstrap-select-1.9.4/dist/css/bootstrap-select.min.css',
            '113_' => 'vendor/bootstrap-select-1.13.2/dist/css/bootstrap-select.min.css',
            '121_' => 'vendor/vakata-jstree-3.3.4/dist/themes/proton/style.min.css',
        ],
        'images' => [
            '100_' => 'img/PrEECoG.svg',
        ]
    ],
];
