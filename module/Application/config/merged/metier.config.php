<?php

namespace Application;

use Application\Controller\MetierController;
use Application\Controller\MetierControllerFactory;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\DomaineFormFactory;
use Application\Form\RessourceRh\DomaineHydrator;
use Application\Form\RessourceRh\DomaineHydratorFactory;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\FamilleProfessionnelleFormFactory;
use Application\Form\RessourceRh\FamilleProfessionnelleHydrator;
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
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'index'
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'ajouter-domaine',
                        'modifier-domaine',
                        'ajouter-famille',
                        'modifier-famille',
                        'ajouter-metier',
                        'modifier-metier',
                    ],
                    'privileges' => [
                        RessourceRhPrivileges::MODIFIER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'effacer-domaine',
                        'effacer-famille',
                        'effacer-metier',
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
                        'pages' => [
                            [
                                'order' => 600,
                                'label' => 'Familles, domaines et métiers',
                                'route' => 'metier',
                                'resource' => PrivilegeController::getResourceId(MetierController::class, 'index') ,
                                'dropdown-header' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
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

                    /** FAMILLE ***************************************************************************************/

                    'ajouter-famille' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter-famille',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'ajouter-famille',
                            ],
                        ],
                    ],
                    'modifier-famille' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-famille/:famille',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'modifier-famille',
                            ],
                        ],
                    ],
                    'effacer-famille' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer-famille/:famille',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'effacer-famille',
                            ],
                        ],
                    ],

                    /** DOMAINE ***************************************************************************************/

                    'ajouter-domaine' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter-domaine',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'ajouter-domaine',
                            ],
                        ],
                    ],
                    'modifier-domaine' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-domaine/:domaine',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'modifier-domaine',
                            ],
                        ],
                    ],
                    'effacer-domaine' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer-domaine/:domaine',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'effacer-domaine',
                            ],
                        ],
                    ],

                    /** METIER ****************************************************************************************/

                    'ajouter-metier' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter-metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'ajouter-metier',
                            ],
                        ],
                    ],
                    'modifier-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-metier/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'modifier-metier',
                            ],
                        ],
                    ],
                    'effacer-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer-metier/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'effacer-metier',
                            ],
                        ],
                    ],

                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            DomaineService::class => DomaineServiceFactory::class,
            FamilleProfessionnelleService::class => FamilleProfessionnelleServiceFactory::class,
            MetierService::class => MetierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MetierController::class => MetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            DomaineForm::class => DomaineFormFactory::class,
            FamilleProfessionnelleForm::class => FamilleProfessionnelleFormFactory::class,
//            FonctionForm::class => FonctionFormFactory::class,
            MetierForm::class => MetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            FamilleProfessionnelleHydrator::class => FamilleProfessionnelleHydrator::class,
        ],
        'factories' => [
            DomaineHydrator::class => DomaineHydratorFactory::class,
//            FonctionHydrator::class => FonctionHydratorFactory::class,
            MetierHydrator::class => MetierHydratorFactory::class,
        ],
    ]
];
