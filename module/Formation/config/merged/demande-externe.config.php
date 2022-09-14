<?php

namespace Formation;

use Formation\Assertion\DemandeExterneAssertion;
use Formation\Assertion\DemandeExterneAssertionFactory;
use Formation\Controller\DemandeExterneController;
use Formation\Controller\DemandeExterneControllerFactory;
use Formation\Form\DemandeExterne\DemandeExterneForm;
use Formation\Form\DemandeExterne\DemandeExterneFormFactory;
use Formation\Form\DemandeExterne\DemandeExterneHydrator;
use Formation\Form\DemandeExterne\DemandeExterneHydratorFactory;
use Formation\Provider\Privilege\DemandeexternePrivileges;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\DemandeExterne\DemandeExterneServiceFactory;
use Formation\View\Helper\DemandeExterneViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'DemandeExterne' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            DemandeexternePrivileges::DEMANDEEXTERNE_AFFICHER,
                            DemandeexternePrivileges::DEMANDEEXTERNE_AJOUTER,
                            DemandeexternePrivileges::DEMANDEEXTERNE_MODIFIER,
                            DemandeexternePrivileges::DEMANDEEXTERNE_HISTORISER,
                            DemandeexternePrivileges::DEMANDEEXTERNE_SUPPRIMER,
                            DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_AGENT,
                            DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_RESPONSABLE,
                            DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_DRH,
                        ],
                        'resources' => ['DemandeExterne'],
                        'assertion' => DemandeExterneAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'index',
                        'rechercher-agent',
                        'rechercher-organisme',
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
                    'assertion' => DemandeExterneAssertion::class
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_AJOUTER
                    ],
                    'assertion' => DemandeExterneAssertion::class
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'modifier',
                        'ajouter-devis',
                        'retirer-devis',
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
                    'assertion' => DemandeExterneAssertion::class
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_SUPPRIMER,
                    ],
                    'assertion' => DemandeExterneAssertion::class
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'valider-agent',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_AGENT
                    ],
                    'assertion' => DemandeExterneAssertion::class
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'valider-responsable',
                        'refuser-responsable',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_RESPONSABLE
                    ],
                    'assertion' => DemandeExterneAssertion::class
                ],
                [
                    'controller' => DemandeExterneController::class,
                    'action' => [
                        'valider-drh',
                        'refuser-drh',
                    ],
                    'privileges' => [
                        DemandeexternePrivileges::DEMANDEEXTERNE_VALIDER_DRH
                    ],
                    'assertion' => DemandeExterneAssertion::class
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'demande-externe' => [
                                'label'    => 'Demandes externes',
                                'route'    => 'formation/demande-externe',
                                'resource' => PrivilegeController::getResourceId(DemandeExterneController::class, 'index') ,
                                'order'    => 331,
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
                            'ajouter-devis' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter-devis/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'ajouter-devis'
                                    ],
                                ],
                            ],
                            'retirer-devis' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/retirer-devis/:devis',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'retirer-devis'
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
                            'refuser-responsable' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/refuser-responsable/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'refuser-responsable'
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
                            'refuser-drh' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/refuser-drh/:demande-externe',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'refuser-drh'
                                    ],
                                ],
                            ],
                            'rechercher-agent' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/rechercher-agent',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'rechercher-agent'
                                    ],
                                ],
                            ],
                            'rechercher-organisme' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/rechercher-organisme',
                                    'defaults' => [
                                        'controller' => DemandeExterneController::class,
                                        'action' => 'rechercher-organisme'
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
            DemandeExterneAssertion::class => DemandeExterneAssertionFactory::class,
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
    ],
    'view_helpers' => [
        'invokables' => [
            'demandeExterne' => DemandeExterneViewHelper::class,
        ]
    ]

];