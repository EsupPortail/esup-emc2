<?php

namespace Carriere;

use Carriere\Controller\IndexController;
use Carriere\Controller\IndexControllerFactory;
use Carriere\Provider\Privilege\CategoriePrivileges;
use Carriere\Provider\Privilege\CorpsPrivileges;
use Carriere\Provider\Privilege\CorrespondancePrivileges;
use Carriere\Provider\Privilege\EmploiTypePrivileges;
use Carriere\Provider\Privilege\FamilleprofessionnellePrivileges;
use Carriere\Provider\Privilege\GradePrivileges;
use Carriere\Provider\Privilege\NiveaucarrierePrivileges;
use Carriere\Provider\Privilege\NiveaufonctionPrivileges;
//use FicheMetier\Provider\Privilege\CodeFonctionPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => IndexController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CorrespondancePrivileges::CORRESPONDANCE_INDEX,
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_INDEX,
                        CategoriePrivileges::CATEGORIE_INDEX,
                        CorpsPrivileges::CORPS_INDEX,
                        EmploiTypePrivileges::EMPLOITYPE_INDEX,
                        GradePrivileges::GRADE_INDEX,
                        NiveaucarrierePrivileges::NIVEAUCARRIERE_INDEX,
                        NiveaufonctionPrivileges::NIVEAUFONCTION_INDEX,
//                        CodeFonctionPrivileges::CODEFONCTION_INDEX, // dans un module chargé après
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'carriere' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/carriere',
                    'defaults' => [
                        /** @see IndexController::indexAction() */
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'order' => 2000,
                                'label' => 'Ressources liées à la carrière',
                                'route' => 'carriere',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index') ,
                                'icon' => 'fas fa-angle-right',
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];