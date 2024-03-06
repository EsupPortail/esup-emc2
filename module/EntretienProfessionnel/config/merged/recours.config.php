<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\RecoursController;
use EntretienProfessionnel\Controller\RecoursControllerFactory;
use EntretienProfessionnel\Form\Recours\RecoursForm;
use EntretienProfessionnel\Form\Recours\RecoursFormFactory;
use EntretienProfessionnel\Form\Recours\RecoursHydrator;
use EntretienProfessionnel\Form\Recours\RecoursHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\RecoursPrivileges;
use EntretienProfessionnel\Service\Recours\RecoursService;
use EntretienProfessionnel\Service\Recours\RecoursServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => RecoursController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        RecoursPrivileges::RECOURS_AJOUTER,
                    ],
                ],
                [
                    'controller' => RecoursController::class,
                    'action' => [
                        'modifier',
                        'toggleModifiable'
                    ],
                    'privileges' => [
                        RecoursPrivileges::RECOURS_MODIFIER,
                    ],
                ],
                [
                    'controller' => RecoursController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        RecoursPrivileges::RECOURS_HISTORISER,
                    ],
                ],
                [
                    'controller' => RecoursController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        RecoursPrivileges::RECOURS_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'entretien-professionnel' => [
                'options' => [
                    'route'    => '/entretien-professionnel',
                ],
                'child_routes' => [
                    'recours' => [
                        'type'  => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/recours',
                            'defaults' => [
                                'controller' => RecoursController::class,
                            ],
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter/:entretien-professionnel',
                                    'defaults' => [
                                        /** @see RecoursController::ajouterAction() */
                                        'controller' => RecoursController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:recours',
                                    'defaults' => [
                                        /** @see RecoursController::modifierAction() */
                                        'controller' => RecoursController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'toggle-modifiable' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/toggle-modifiable/:recours',
                                    'defaults' => [
                                        /** @see RecoursController::toggleModifiableAction() */
                                        'controller' => RecoursController::class,
                                        'action'     => 'toggle-modifiable',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:recours',
                                    'defaults' => [
                                        /** @see RecoursController::historiserAction() */
                                        'controller' => RecoursController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:recours',
                                    'defaults' => [
                                        /** @see RecoursController::restaurerAction() */
                                        'controller' => RecoursController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/supprimer/:recours',
                                    'defaults' => [
                                        /** @see RecoursController::supprimerAction() */
                                        'controller' => RecoursController::class,
                                        'action'     => 'supprimer',
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
            RecoursService::class => RecoursServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            RecoursController::class => RecoursControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            RecoursForm::class => RecoursFormFactory::class
        ],
    ],
    'hydrators' => [
        'factories' => [
            RecoursHydrator::class => RecoursHydratorFactory::class,
        ],
    ]

];