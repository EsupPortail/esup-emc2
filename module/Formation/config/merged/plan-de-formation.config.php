<?php

namespace Formation;

use Formation\Controller\PlanDeFormationController;
use Formation\Controller\PlanDeFormationControllerFactory;
use Formation\Form\PlanDeFormation\PlanDeFormationForm;
use Formation\Form\PlanDeFormation\PlanDeFormationFormFactory;
use Formation\Form\PlanDeFormation\PlanDeFormationHydrator;
use Formation\Form\PlanDeFormation\PlanDeFormationHydratorFactory;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationForm;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationFormFactory;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationHydrator;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationHydratorFactory;
use Formation\Provider\Privilege\PlanformationPrivileges;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'courant',
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_COURANT
                    ],
                ],
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_INDEX
                    ],
                ],
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_AFFICHER
                    ],
                ],
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_AJOUTER
                    ],
                ],
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_MODIFIER
                    ],
                ],
                [
                    'controller' => PlanDeFormationController::class,
                    'action' => [
                        'gerer-formations',
                        'reprendre',
                        'supprimer'
                    ],
                    'privileges' => [
                        PlanformationPrivileges::PLANFORMATION_SUPPRIMER
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'planformation_header' =>[
                                'label' => "Gestion du plan de formation",
                                'route' => 'home',
                                'resource' =>
                                    //PrivilegeController::getResourceId(PlanDeFormationController::class, 'index') ,
                                    PlanformationPrivileges::getResourceId(PlanformationPrivileges::PLANFORMATION_INDEX),
                                'order'    => 100,
                                'dropdown-header' => true,
                            ],
                            'planformation_courrant' => [
                                'label'    => 'Plan de formation courant',
                                'route'    => 'plan-de-formation/courant',
                                'resource' => PrivilegeController::getResourceId(PlanDeFormationController::class, 'index') ,
                                'order'    => 110,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'plansformation_all' => [
                                'label'    => 'Plans de formation',
                                'route'    => 'plan-de-formation',
                                'resource' => PrivilegeController::getResourceId(PlanDeFormationController::class, 'index') ,
                                'order'    => 120,
                                'icon' => 'fas fa-angle-right',
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'plan-de-formation' => [
                'type'  => Segment::class,
                'options' => [
                    /** @see PlanDeFormationController::indexAction() */
                    'route'    => '/plan-de-formation',
                    'defaults' => [
                        'controller' => PlanDeFormationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'courant' => [
                        'type'  => Literal::class,
                        'options' => [
                            /** @see PlanDeFormationController::courantAction() */
                            'route'    => '/courant',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'courant',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            /** @see PlanDeFormationController::ajouterAction() */
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'gerer-formations' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see PlanDeFormationController::gererFormationsAction() */
                            'route'    => '/gerer-formations/:plan-de-formation',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'ajouter-formation',
                            ],
                        ],
                    ],
                    'reprendre' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see PlanDeFormationController::reprendreAction() */
                            'route'    => '/reprendre/:plan-de-formation',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'reprendre',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see PlanDeFormationController::afficherAction() */
                            'route'    => '/afficher/:plan-de-formation',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see PlanDeFormationController::modifierAction() */
                            'route'    => '/modifier/:plan-de-formation',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            /** @see PlanDeFormationController::supprimer() */
                            'route'    => '/supprimer/:plan-de-formation',
                            'defaults' => [
                                'controller' => PlanDeFormationController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],

                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            PlanDeFormationService::class => PlanDeFormationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            PlanDeFormationController::class => PlanDeFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            PlanDeFormationForm::class => PlanDeFormationFormFactory::class,
            SelectionPlanDeFormationForm::class => SelectionPlanDeFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            PlanDeFormationHydrator::class => PlanDeFormationHydratorFactory::class,
            SelectionPlanDeFormationHydrator::class => SelectionPlanDeFormationHydratorFactory::class,
        ],
    ]

];