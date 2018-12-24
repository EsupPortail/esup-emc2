<?php

namespace Application;

use Application\Controller\Metier\MetierController;
use Application\Controller\RessourceRh\RessourceRhController;
use Application\Controller\RessourceRh\RessourceRhControllerFactory;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\DomaineFormFactory;
use Application\Form\RessourceRh\DomaineHydrator;
use Application\Form\RessourceRh\FonctionForm;
use Application\Form\RessourceRh\FonctionFormFactory;
use Application\Form\RessourceRh\FonctionHydrator;
use Application\Form\RessourceRh\GradeForm;
use Application\Form\RessourceRh\GradeFormFactory;
use Application\Form\RessourceRh\GradeHydrator;
use Application\Form\RessourceRh\GradeHydratorFactory;
use Application\Form\RessourceRh\MetierFamilleForm;
use Application\Form\RessourceRh\MetierFamilleFormFactory;
use Application\Form\RessourceRh\MetierFamilleHydrator;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\MetierFormFactory;
use Application\Form\RessourceRh\MetierHydrator;
use Application\Form\RessourceRh\AgentStatusForm;
use Application\Form\RessourceRh\AgentStatusFormFactory;
use Application\Form\RessourceRh\AgentStatusHydrator;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorpsFormFactory;
use Application\Form\RessourceRh\CorpsHydrator;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Form\RessourceRh\CorrespondanceFormFactory;
use Application\Form\RessourceRh\CorrespondanceHydrator;
use Application\Form\RessourceRh\MetierHydratorFactory;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\RessourceRh\RessourceRhServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'index',
                        'get-grades-json',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'creer-agent-status',
                        'creer-correspondance',
                        'creer-corps',
                        'creer-metier',
                        'creer-famille',
                        'ajouter-domaine',
                        'ajouter-fonction',
                        'ajouter-grade',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'modifier-agent-status',
                        'modifier-correspondance',
                        'modifier-corps',
                        'modifier-metier',
                        'modifier-famille',
                        'modifier-domaine',
                        'modifier-fonction',
                        'modifier-grade',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::MODIFIER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'effacer-corps',
                        'effacer-correspondance',
                        'effacer-metier',
                        'effacer-agent-status',
                        'effacer-famille',
                        'supprimer-domaine',
                        'supprimer-fonction',
                        'supprimer-grade',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'ressource-rh' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/ressource-rh',
                    'defaults' => [
                        'controller' => RessourceRhController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'agent-status' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/agent-status',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-agent-status',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-agent-status',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-agent-status',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'correspondance' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/correspondance',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-correspondance',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-correspondance',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-correspondance',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'corps' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/corps',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-corps',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-corps',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-corps',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'metier' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-metier',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-metier',
                                    ],
                                ],
                            ],
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-metier',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'famille' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/famille',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'creer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/creer',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'creer-famille',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-famille',
                                    ],
                                ],
                            ],
                            'effacer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/effacer/:id',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'effacer-famille',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'domaine' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/domaine',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'ajouter-domaine',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:domaine',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-domaine',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:domaine',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'supprimer-domaine',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'fonction' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/fonction',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'ajouter-fonction',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:fonction',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-fonction',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:fonction',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'supprimer-fonction',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'grade' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/grade',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get-grades' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/get-grades',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'get-grades-json',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'ajouter-grade',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:grade',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-grade',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:grade',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'supprimer-grade',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            RessourceRhService::class => RessourceRhServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RessourceRhController::class => RessourceRhControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentStatusForm::class => AgentStatusFormFactory::class,
            CorpsForm::class => CorpsFormFactory::class,
            CorrespondanceForm::class => CorrespondanceFormFactory::class,
            MetierForm::class => MetierFormFactory::class,
            MetierFamilleForm::class => MetierFamilleFormFactory::class,
            DomaineForm::class => DomaineFormFactory::class,
            FonctionForm::class => FonctionFormFactory::class,
            GradeForm::class => GradeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            AgentStatusHydrator::class => AgentStatusHydrator::class,
            CorpsHydrator::class => CorpsHydrator::class,
            CorrespondanceHydrator::class => CorrespondanceHydrator::class,
            MetierFamilleHydrator::class => MetierFamilleHydrator::class,
            DomaineHydrator::class => DomaineHydrator::class,
            FonctionHydrator::class => FonctionHydrator::class,
        ],
        'factories' => [
            MetierHydrator::class => MetierHydratorFactory::class,
            GradeHydrator::class => GradeHydratorFactory::class,
        ],
    ]

];