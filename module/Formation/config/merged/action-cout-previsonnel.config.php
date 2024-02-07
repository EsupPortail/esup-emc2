<?php

namespace Formation;

use Formation\Controller\ActionCoutPrevisionnelController;
use Formation\Controller\ActionCoutPrevisionnelControllerFactory;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelForm;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelFormFactory;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelHydrator;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelHydratorFactory;
use Formation\Provider\Privilege\CoutprevisionnelPrivileges;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelService;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelServiceFactory;
use Formation\View\Helper\CoutsPrevisionnelsViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ActionCoutPrevisionnelController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CoutprevisionnelPrivileges::COUTPREVISIONNEL_INDEX,
                    ],
                ],
                [
                    'controller' => ActionCoutPrevisionnelController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CoutprevisionnelPrivileges::COUTPREVISIONNEL_AFFICHER,
                    ],
                ],
                [
                    'controller' => ActionCoutPrevisionnelController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CoutprevisionnelPrivileges::COUTPREVISIONNEL_AJOUTER,
                    ],
                ],
                [
                    'controller' => ActionCoutPrevisionnelController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CoutprevisionnelPrivileges::COUTPREVISIONNEL_MODIFIER,
                    ],
                ],
                [
                    'controller' => ActionCoutPrevisionnelController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CoutprevisionnelPrivileges::COUTPREVISIONNEL_HISTORISER,
                    ],
                ],
                [
                    'controller' => ActionCoutPrevisionnelController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        CoutprevisionnelPrivileges::COUTPREVISIONNEL_SUPPRIMER,
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
                            'couts_previsionnels' =>[
                                'label' => "Coûts prévisionnels",
                                'route' => 'formation/action-cout-previsionnel',
                                'resource' => PrivilegeController::getResourceId(ActionCoutPrevisionnelController::class, 'index') ,
                                'order'    => 130,
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
            'formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation',
                ],
                'child_routes' => [
                    'action-cout-previsionnel' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/action-cout-previsionnel',
                            'defaults' => [
                                /** @see ActionCoutPrevisionnelController::indexAction() */
                                'controller' => ActionCoutPrevisionnelController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter[/:action-de-formation][/:plan-de-formation]',
                                    'defaults' => [
                                        /** @see ActionCoutPrevisionnelController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:action-cout-previsionnel',
                                    'defaults' => [
                                        /** @see ActionCoutPrevisionnelController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:action-cout-previsionnel',
                                    'defaults' => [
                                        /** @see ActionCoutPrevisionnelController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:action-cout-previsionnel',
                                    'defaults' => [
                                        /** @see ActionCoutPrevisionnelController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:action-cout-previsionnel',
                                    'defaults' => [
                                        /** @see ActionCoutPrevisionnelController::supprimerAction() */
                                        'action' => 'supprimer',
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
            ActionCoutPrevisionnelService::class => ActionCoutPrevisionnelServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ActionCoutPrevisionnelController::class => ActionCoutPrevisionnelControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ActionCoutPrevisionnelForm::class => ActionCoutPrevisionnelFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ActionCoutPrevisionnelHydrator::class => ActionCoutPrevisionnelHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'coutsPrevisionnels' => CoutsPrevisionnelsViewHelper::class,
        ],
    ],
];