<?php

namespace Application;

use Application\Controller\RessourceRhController;
use Application\Controller\RessourceRhControllerFactory;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\DomaineFormFactory;
use Application\Form\RessourceRh\DomaineHydrator;
use Application\Form\RessourceRh\DomaineHydratorFactory;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\FamilleProfessionnelleFormFactory;
use Application\Form\RessourceRh\FamilleProfessionnelleHydrator;
use Application\Form\RessourceRh\FonctionHydrator;
use Application\Form\RessourceRh\FonctionHydratorFactory;
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
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'index',
                        'index-grade',
                        'index-corps',
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
                        'creer-metier',
                        'creer-famille',
                        'ajouter-domaine',
                        'ajouter-fonction',

                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'modifier-metier',
                        'modifier-famille',
                        'modifier-domaine',
                        'modifier-fonction',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::MODIFIER,
                    ],
                ],
                [
                    'controller' => RessourceRhController::class,
                    'action' => [
                        'effacer-metier',
                        'effacer-famille',
                        'supprimer-domaine',
                        'supprimer-fonction',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::EFFACER,
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
                        'order' => 500,
                        'label' => 'Ressources',
                        'title' => "Ressources",
                        'route' => 'ressource-rh',
                        'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
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
                    'index-corps' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-corps',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'index-corps',
                            ],
                        ],
                    ],
                    'index-grade' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-grade',
                            'defaults' => [
                                'controller' => RessourceRhController::class,
                                'action'     => 'index-grade',
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
            MetierForm::class => MetierFormFactory::class,
            FamilleProfessionnelleForm::class => FamilleProfessionnelleFormFactory::class,
            DomaineForm::class => DomaineFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            FamilleProfessionnelleHydrator::class => FamilleProfessionnelleHydrator::class,
        ],
        'factories' => [
            MetierHydrator::class => MetierHydratorFactory::class,
            DomaineHydrator::class => DomaineHydratorFactory::class,
            FonctionHydrator::class => FonctionHydratorFactory::class,
        ],
    ]
];