<?php

namespace Application;

use Application\Controller\MetierController;
use Application\Controller\MetierControllerFactory;
use Application\Form\Domaine\DomaineForm;
use Application\Form\Domaine\DomaineFormFactory;
use Application\Form\Domaine\DomaineHydrator;
use Application\Form\Domaine\DomaineHydratorFactory;
use Application\Form\Metier\MetierForm;
use Application\Form\Metier\MetierFormFactory;
use Application\Form\Metier\MetierHydrator;
use Application\Form\Metier\MetierHydratorFactory;
use Application\Provider\Privilege\MetierPrivileges;
use Application\Service\Domaine\DomaineService;
use Application\Service\Domaine\DomaineServiceFactory;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceFactory;
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
                        MetierPrivileges::METIER_AFFICHER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'ajouter-domaine',
                        'ajouter-famille',
                        'ajouter-metier',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'modifier-domaine',
                        'modifier-famille',
                        'modifier-metier',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'historiser-domaine',
                        'historiser-famille',
                        'historiser-metier',
                        'restaurer-domaine',
                        'restaurer-famille',
                        'restaurer-metier',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_HISTORISER,
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
                        MetierPrivileges::METIER_DETRUIRE,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'cartographie',
                        'export-cartographie',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_CARTOGRAPHIE,
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
                                'pages' => [
                                    [
                                        'route' => 'ressource-rh/cartographie',
                                    ],
                                 ],
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
                    'historiser-famille' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-famille/:famille',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'historiser-famille',
                            ],
                        ],
                    ],
                    'restaurer-famille' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-famille/:famille',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'restaurer-famille',
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
                    'historiser-domaine' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-domaine/:domaine',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'historiser-domaine',
                            ],
                        ],
                    ],
                    'restaurer-domaine' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-domaine/:domaine',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'restaurer-domaine',
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
                    'historiser-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-metier/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'historiser-metier',
                            ],
                        ],
                    ],
                    'restaurer-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-metier/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'restaurer-metier',
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

                    /** CARTOGRAPHIE **********************************************************************************/

                    'cartographie' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/cartographie',
                            'defaults' => [
                                'controller' => MetierController::class,
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
                                        'controller' => MetierController::class,
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
            MetierForm::class => MetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            DomaineHydrator::class => DomaineHydratorFactory::class,
            MetierHydrator::class => MetierHydratorFactory::class,
        ],
    ]
];
