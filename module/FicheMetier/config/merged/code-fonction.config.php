<?php

namespace FicheMetier;

use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Form\CodeFonction\CodeFonctionFormFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionHydrator;
use FicheMetier\Form\CodeFonction\CodeFonctionHydratorFactory;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
            ],
        ],
    ],

//    'navigation' => [
//        'default' => [
//            'home' => [
//                'pages' => [
//                    'gestion' => [
//                        'pages' => [
//                            'activite' => [
//                                'label' => 'Activités',
//                                'route' => 'activite',
//                                'resource' => PrivilegeController::getResourceId(ActiviteController::class, 'index'),
//                                'order' => 1010,
//                                'icon' => 'fas fa-angle-right',
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],

    'router' => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            CodeFonctionService::class => CodeFonctionServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
        ],
    ],
    'form_elements' => [
        'factories' => [
            CodeFonctionForm::class => CodeFonctionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CodeFonctionHydrator::class => CodeFonctionHydratorFactory::class,
        ],
    ],

];