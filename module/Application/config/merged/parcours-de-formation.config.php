<?php

namespace Application;

use Application\Controller\ParcoursDeFormationController;
use Application\Controller\ParcoursDeFormationControllerFactory;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationForm;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationFormFactory;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationHydrator;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationHydratorFactory;
use Application\Provider\Privilege\FormationPrivileges;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ParcoursDeFormationController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'modifier',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INDEX,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'parcours-de-formation' => [
                                'label'    => 'Parcours de formation',
                                'route'    => 'parcours-de-formation',
                                'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_AFFICHER),
                                'order'    => 1150,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'parcours-de-formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/parcours-de-formation',
                    'defaults' => [
                        'controller' => ParcoursDeFormationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ParcoursDeFormationService::class => ParcoursDeFormationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ParcoursDeFormationController::class => ParcoursDeFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ParcoursDeFormationForm::class => ParcoursDeFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ParcoursDeFormationHydrator::class => ParcoursDeFormationHydratorFactory::class,
        ],
    ]

];