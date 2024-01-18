<?php

namespace MissionSpecifique;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Observation\Controller\ObservationInstanceController;
use Observation\Controller\ObservationInstanceControllerFactory;
use Observation\Form\ObservationInstance\ObservationInstanceForm;
use Observation\Form\ObservationInstance\ObservationInstanceFormFactory;
use Observation\Form\ObservationInstance\ObservationInstanceHydrator;
use Observation\Form\ObservationInstance\ObservationInstanceHydratorFactory;
use Observation\Provider\Privilege\ObservationinstancePrivileges;
use Observation\Service\ObservationInstance\ObservationInstanceService;
use Observation\Service\ObservationInstance\ObservationInstanceServiceFactory;
use Observation\View\Helper\ObservationInstanceViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ObservationInstanceController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ObservationinstancePrivileges::OBSERVATIONINSTANCE_INDEX,
                    ],
                ],
                [
                    'controller' => ObservationInstanceController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ObservationinstancePrivileges::OBSERVATIONINSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => ObservationInstanceController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ObservationinstancePrivileges::OBSERVATIONINSTANCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => ObservationInstanceController::class,
                    'action' => [
                        'modifier',
                        'valider',
                    ],
                    'privileges' => [
                        ObservationinstancePrivileges::OBSERVATIONINSTANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ObservationInstanceController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ObservationinstancePrivileges::OBSERVATIONINSTANCE_HISTORISER,
                    ],
                ],
                [
                    'controller' => ObservationInstanceController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ObservationinstancePrivileges::OBSERVATIONINSTANCE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],


    'router'          => [
        'routes' => [
            'observation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/observation',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'instance' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/instance',
                            'defaults' => [
                                /** @see ObservationInstanceController::indexAction() */
                                'controller' => ObservationInstanceController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:observation-instance',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::afficherAction() */
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::ajouterAction() */
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:observation-instance',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::modifierAction() */
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:observation-instance',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::historiserAction() */
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:observation-instance',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::restaurerAction() */
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:observation-instance',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::supprimerAction() */
                                        'action'     => 'supprimer',
                                    ],
                                ],
                            ],
                            'valider' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/valider/:observation-instance',
                                    'defaults' => [
                                        /** @see ObservationInstanceController::validerAction() */
                                        'action'     => 'valider',
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
            ObservationInstanceService::class => ObservationInstanceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ObservationInstanceController::class => ObservationInstanceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ObservationInstanceForm::class => ObservationInstanceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ObservationInstanceHydrator::class => ObservationInstanceHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'observationinstance' => ObservationInstanceViewHelper::class,
        ],
    ],

];