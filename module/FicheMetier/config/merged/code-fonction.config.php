<?php

namespace FicheMetier;

use FicheMetier\Controller\CodeFonctionController;
use FicheMetier\Controller\CodeFonctionControllerFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Form\CodeFonction\CodeFonctionFormFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionHydrator;
use FicheMetier\Form\CodeFonction\CodeFonctionHydratorFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceFactory;
use Laminas\Router\Http\Literal;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_INDEX, //todo privileges
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'code-fonction' => [
                                'label' => 'Code fonction',
                                'route' => 'code-fonction',
                                'resource' => PrivilegeController::getResourceId(CodeFonctionController::class, 'index'),
                                'order' => 2502,
                                'pages' => [],
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'code-fonction' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/code-fonction',
                    'defaults' => [
                        /** @see CodeFonctionController::indexAction() */
                        'controller' => CodeFonctionController::class,
                        'action' => 'index',
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
            CodeFonctionService::class => CodeFonctionServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CodeFonctionController::class => CodeFonctionControllerFactory::class,
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