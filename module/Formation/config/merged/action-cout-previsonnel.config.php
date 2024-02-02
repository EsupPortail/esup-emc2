<?php

namespace Formation;

use Formation\Controller\ActionCoutPrevisionnelController;
use Formation\Controller\ActionCoutPrevisionnelControllerFactory;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelForm;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelFormFactory;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelHydrator;
use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelHydratorFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelService;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelServiceFactory;
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
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AFFICHER,
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

];