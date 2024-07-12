<?php

namespace FicheReferentiel;

use FicheReferentiel\Controller\ReferensImporterController;
use FicheReferentiel\Controller\ReferensImporterControllerFactory;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ReferensImporterController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [

                    ],
                ],
            ],
        ],
    ],

//    'navigation'      => [
//        'default' => [
//            'home' => [
//                'pages' => [
//                    'gestion' => [
//                        'pages' => [
//                            'fiche-metier' => [
//                                'label' => 'Fiches métiers',
//                                'route' => 'fiche-metier',
//                                'resource' =>  FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX) ,
//                                'order'    => 2020,
//                                'icon' => 'fas fa-angle-right',
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],

    'router'          => [
        'routes' => [
            'referens-importer' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/referens-importer',
                    'defaults' => [
                        /** @see ReferensImporterController::indexAction() */
                        'controller' => ReferensImporterController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            ReferensImporterController::class => ReferensImporterControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ]

];