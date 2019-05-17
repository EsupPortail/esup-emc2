<?php

namespace Application;

use Application\Controller\RessourceRh\RessourceRhController;
use Application\Controller\RessourceRh\RessourceRhControllerFactory;

use Application\Form\MissionSpecifique\MissionSpecifiqueForm;
use Application\Form\MissionSpecifique\MissionSpecifiqueFormFactory;
use Application\Form\MissionSpecifique\MissionSpecifiqueHydrator;
use Application\Form\MissionSpecifique\MissionSpecifiqueHydratorFactory;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorpsFormFactory;
use Application\Form\RessourceRh\CorpsHydrator;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Form\RessourceRh\CorrespondanceFormFactory;
use Application\Form\RessourceRh\CorrespondanceHydrator;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\DomaineFormFactory;
use Application\Form\RessourceRh\DomaineHydrator;
use Application\Form\RessourceRh\DomaineHydratorFactory;
use Application\Form\RessourceRh\FamilleProfessionnelleHydrator;
use Application\Form\RessourceRh\FonctionForm;
use Application\Form\RessourceRh\FonctionFormFactory;
use Application\Form\RessourceRh\FonctionHydrator;
use Application\Form\RessourceRh\FonctionHydratorFactory;
use Application\Form\RessourceRh\GradeForm;
use Application\Form\RessourceRh\GradeFormFactory;
use Application\Form\RessourceRh\GradeHydrator;
use Application\Form\RessourceRh\GradeHydratorFactory;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\FamilleProfessionnelleFormFactory;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\MetierFormFactory;
use Application\Form\RessourceRh\MetierHydrator;
use Application\Form\RessourceRh\MetierHydratorFactory;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Service\Domaine\DomaineService;
use Application\Service\Domaine\DomaineServiceFactory;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceFactory;
use Application\Service\Fonction\FonctionService;
use Application\Service\Fonction\FonctionServiceFactory;
use Application\Service\Metier\MetierService;
use Application\Service\Metier\MetierServiceFactory;
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
                        'index-corps-grade',
                        'index-metier-famille-domaine',
                        'index-correspondance',
                        'index-mission-specifique',
                        'get-grades-json',
                        'cartographie',
                        'export-cartographie',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'creer-correspondance',
                        'creer-corps',
                        'creer-metier',
                        'creer-famille',
                        'ajouter-domaine',
                        'ajouter-fonction',
                        'ajouter-grade',
                        'ajouter-mission-specifique',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'modifier-correspondance',
                        'modifier-corps',
                        'modifier-metier',
                        'modifier-famille',
                        'modifier-domaine',
                        'modifier-fonction',
                        'modifier-grade',

                        'modifier-mission-specifique',
                        'historiser-mission-specifique',
                        'restaurer-mission-specifique',
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
                        'effacer-famille',
                        'supprimer-domaine',
                        'supprimer-fonction',
                        'supprimer-grade',
                        'supprimer-mission-specifique',
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
                    'index-corps-grade' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-corps-grade',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'index-corps-grade',
                            ],
                        ],
                    ],
                    'index-metier-famille-domaine' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-metier-famille-domaine',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'index-metier-famille-domaine',
                            ],
                        ],
                    ],
                    'index-correspondance' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-correspondance',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'index-correspondance',
                            ],
                        ],
                    ],
                    'index-mission-specifique' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-mission-specifique',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'index-mission-specifique',
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
                    'cartographie' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/cartographie',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'cartographie',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'export' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route'    => '/export',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'export-cartographie',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                    'mission-specifique' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/mission-specifique',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'ajouter-mission-specifique',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:mission',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'modifier-mission-specifique',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:mission',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'historiser-mission-specifique',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:mission',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'restaurer-mission-specifique',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:mission',
                                    'defaults' => [
                                        'controller' => RessourceRhController::class,
                                        'action'     => 'supprimer-mission-specifique',
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
        'factories' => [
            RessourceRhService::class => RessourceRhServiceFactory::class,
            DomaineService::class => DomaineServiceFactory::class,
            FamilleProfessionnelleService::class => FamilleProfessionnelleServiceFactory::class,
            FonctionService::class => FonctionServiceFactory::class,
            MetierService::class => MetierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RessourceRhController::class => RessourceRhControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CorpsForm::class => CorpsFormFactory::class,
            CorrespondanceForm::class => CorrespondanceFormFactory::class,
            MetierForm::class => MetierFormFactory::class,
            FamilleProfessionnelleForm::class => FamilleProfessionnelleFormFactory::class,
            FonctionForm::class => FonctionFormFactory::class,
            DomaineForm::class => DomaineFormFactory::class,
            GradeForm::class => GradeFormFactory::class,
            MissionSpecifiqueForm::class => MissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            CorpsHydrator::class => CorpsHydrator::class,
            CorrespondanceHydrator::class => CorrespondanceHydrator::class,
            FamilleProfessionnelleHydrator::class => FamilleProfessionnelleHydrator::class,
//            MissionSpecifiqueHydrator::class => MissionSpecifiqueHydrator::class,
        ],
        'factories' => [
            MetierHydrator::class => MetierHydratorFactory::class,
            GradeHydrator::class => GradeHydratorFactory::class,
            MissionSpecifiqueHydrator::class => MissionSpecifiqueHydratorFactory::class,
            DomaineHydrator::class => DomaineHydratorFactory::class,
            FonctionHydrator::class => FonctionHydratorFactory::class,
        ],
    ]

];