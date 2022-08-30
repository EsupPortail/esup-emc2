<?php

namespace Formation;

use Formation\Controller\DemandeExterneController;
use Formation\Controller\DemandeExterneControllerFactory;
use Formation\Form\DemandeExterne\DemandeExterneForm;
use Formation\Form\DemandeExterne\DemandeExterneFormFactory;
use Formation\Form\DemandeExterne\DemandeExterneHydrator;
use Formation\Form\DemandeExterne\DemandeExterneHydratorFactory;
use Formation\Provider\Privilege\DemandeexternePrivileges;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\DemandeExterne\DemandeExterneServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_INDEX,
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_AFFICHER,
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_AJOUTER
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER,
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_HISTORISER,
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'valider-agent',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_AGENT
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'valider-responsable',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_RESPONSABLE
                    ],
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'valider-drh',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_DRH
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
                'may_terminate' => true,
                'child_routes' => [
                    'demande-externe' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/demande-externe',
                            'defaults' => [
                                'controller' => DemandeExterneController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter/:agent',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'ajouter'
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'afficher'
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'modifier'
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'historiser'
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'restaurer'
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'supprimer'
                                    ],
                                ],
                            ],
                            'valider-agent' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/valider-agent/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'valider-agent'
                                    ],
                                ],
                            ],
                            'valider-responsable' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/valider-responsable/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'valider-responsable'
                                    ],
                                ],
                            ],
                            'valider-drh' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/valider-drh/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'valider-drh'
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
            DemandeExterneService::class => DemandeExterneServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            DemandeExterneController::class => DemandeExterneControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            DemandeExterneForm::class => DemandeExterneFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            DemandeExterneHydrator::class => DemandeExterneHydratorFactory::class,
        ],
    ]

];