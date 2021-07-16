<?php

namespace Metier;

use Application\View\Helper\TypeFonctionViewHelper;
use Metier\Controller\MetierController;
use Metier\Controller\MetierControllerFactory;
use Metier\Form\Metier\MetierForm;
use Metier\Form\Metier\MetierFormFactory;
use Metier\Form\Metier\MetierHydrator;
use Metier\Form\Metier\MetierHydratorFactory;
use Metier\Form\MetierNiveau\MetierNiveauForm;
use Metier\Form\MetierNiveau\MetierNiveauFormFactory;
use Metier\Form\MetierNiveau\MetierNiveauHydrator;
use Metier\Form\MetierNiveau\MetierNiveauHydratorFactory;
use Metier\Provider\Privilege\MetierPrivileges;
use Metier\Service\Metier\MetierService;
use Metier\Service\Metier\MetierServiceFactory;
use Metier\Service\MetierNiveau\MetierNiveauService;
use Metier\Service\MetierNiveau\MetierNiveauServiceFactory;
use Metier\View\Helper\MetierNiveauViewHelper;
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
                        MetierPrivileges::METIER_INDEX,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'modifier',
                        'modifier-niveaux',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'cartographie',
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

                    /** METIER ****************************************************************************************/

                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-niveaux' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-niveaux/:metier',
                            'defaults' => [
                                'controller' => MetierController::class,
                                'action'     => 'modifier-niveaux',
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
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            MetierService::class => MetierServiceFactory::class,
            MetierNiveauService::class => MetierNiveauServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MetierController::class => MetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            MetierForm::class => MetierFormFactory::class,
            MetierNiveauForm::class => MetierNiveauFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            MetierHydrator::class => MetierHydratorFactory::class,
            MetierNiveauHydrator::class => MetierNiveauHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'metierNiveau' => MetierNiveauViewHelper::class,
            'typefonction' => TypeFonctionViewHelper::class,
        ],
    ],
];
