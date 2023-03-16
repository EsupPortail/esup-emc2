<?php

namespace Metier;

use Metier\Controller\ReferenceController;
use Metier\Controller\ReferenceControllerFactory;
use Metier\Form\Reference\ReferenceForm;
use Metier\Form\Reference\ReferenceFormFactory;
use Metier\Form\Reference\ReferenceHydrator;
use Metier\Form\Reference\ReferenceHydraytorFactory;
use Metier\Provider\Privilege\ReferencemetierPrivileges;
use Metier\Service\Reference\ReferenceService;
use Metier\Service\Reference\ReferenceServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ReferenceController::class,
                    'action' => [
                        'index'
                    ],
                    'privileges' => [
                        ReferencemetierPrivileges::REFERENCE_INDEX
                    ],
                ],
                [
                    'controller' => ReferenceController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ReferencemetierPrivileges::REFERENCE_AJOUTER
                    ],
                ],
                [
                    'controller' => ReferenceController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ReferencemetierPrivileges::REFERENCE_MODIFIER
                    ],
                ],
                [
                    'controller' => ReferenceController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ReferencemetierPrivileges::REFERENCE_HISTORISER
                    ],
                ],
                [
                    'controller' => ReferenceController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ReferencemetierPrivileges::REFERENCE_SUPPRIMER
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'metier' => [
                'child_routes' => [
                    'reference' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route' => '/reference',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:metier',
                                    'defaults' => [
                                        /** @see ReferenceController::ajouterAction() */
                                        'controller' => ReferenceController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:reference',
                                    'defaults' => [
                                        /** @see ReferenceController::modifierAction() */
                                        'controller' => ReferenceController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:reference',
                                    'defaults' => [
                                        /** @see ReferenceController::historiserAction() */
                                        'controller' => ReferenceController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:reference',
                                    'defaults' => [
                                        /** @see ReferenceController::restaurerAction() */
                                        'controller' => ReferenceController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:reference',
                                    'defaults' => [
                                        /** @see ReferenceController::supprimerAction() */
                                        'controller' => ReferenceController::class,
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
            ReferenceService::class => ReferenceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ReferenceController::class => ReferenceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ReferenceForm::class => ReferenceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ReferenceHydrator::class => ReferenceHydraytorFactory::class,
        ],
    ]

];