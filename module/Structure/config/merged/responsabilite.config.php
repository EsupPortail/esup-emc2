<?php

namespace Structure;

use Structure\Assertion\ResponsabiliteAssertion;
use Structure\Assertion\ResponsabiliteAssertionFactory;
use Structure\Controller\StructureResponsabiliteController;
use Structure\Controller\StructureResponsabiliteControllerFactory;
use Structure\Form\Responsabilite\ResponsabiliteForm;
use Structure\Form\Responsabilite\ResponsabiliteFormFactory;
use Structure\Form\Responsabilite\ResponsabiliteHydrator;
use Structure\Form\Responsabilite\ResponsabiliteHydratorFactory;
use Structure\Provider\Privilege\ResponsabilitePrivileges;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Service\StructureGestionnaire\StructureGestionnaireService;
use Structure\Service\StructureGestionnaire\StructureGestionnaireServiceFactory;
use Structure\Service\StructureResponsable\StructureResponsableService;
use Structure\Service\StructureResponsable\StructureResponsableServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Responsabilité' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            ResponsabilitePrivileges::RESPONSABILITE_AFFICHER,
                            ResponsabilitePrivileges::RESPONSABILITE_SYNCHRONISER,
                            ResponsabilitePrivileges::RESPONSABILITE_GERER,
                        ],
                        'resources' => ['Responsabilité'],
                        'assertion' => ResponsabiliteAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StructureResponsabiliteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                ],
                [
                    'controller' => StructureResponsabiliteController::class,
                    'action' => [
                        'afficher-responsabilite',
                    ],
                    'privileges' => ResponsabilitePrivileges::RESPONSABILITE_AFFICHER,
                    'assertion' => ResponsabiliteAssertion::class,
                ],
                [
                    'controller' => StructureResponsabiliteController::class,
                    'action' => [ //todo vrai privilege
                        'ajouter',
                        'modifier',
                        'supprimer',
                    ],
                    'privileges' => ResponsabilitePrivileges::RESPONSABILITE_GERER,
                    'assertion' => ResponsabiliteAssertion::class,
                ],
                [
                    'controller' => StructureResponsabiliteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => ResponsabilitePrivileges::RESPONSABILITE_SYNCHRONISER,
                    'assertion' => ResponsabiliteAssertion::class,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'structure' => [
                'child_routes' => [
                    'responsabilite' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/responsabilite',
                            'defaults' => [
                                /** @see StructureResponsabiliteController::indexAction() */
                                'controller' => StructureResponsabiliteController::class,
                                'action' => 'index', //todo
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter/:role[/:structure]',
                                    'defaults' => [
                                        /** @see StructureResponsabiliteController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:responsabilite/:role',
                                    'defaults' => [
                                        /** @see StructureResponsabiliteController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:responsabilite/:role',
                                    'defaults' => [
                                        /** @see StructureResponsabiliteController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:responsabilite/:role',
                                    'defaults' => [
                                        /** @see StructureResponsabiliteController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:responsabilite/:role',
                                    'defaults' => [
                                        /** @see StructureResponsabiliteController::supprimerAction() */
                                        'action' => 'supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'afficher-responsabilite' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher-responsabilite/:role/:structure',
                                    'defaults' => [
                                        /** @see StructureResponsabiliteController::afficherResponsabiliteAction(): */
                                        'controller' => StructureResponsabiliteController::class,
                                        'action' => 'afficher-responsabilite',
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
            ResponsabiliteAssertion::class => ResponsabiliteAssertionFactory::class,

            StructureGestionnaireService::class => StructureGestionnaireServiceFactory::class,
            StructureResponsableService::class => StructureResponsableServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            StructureResponsabiliteController::class => StructureResponsabiliteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ResponsabiliteForm::class  => ResponsabiliteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ResponsabiliteHydrator::class => ResponsabiliteHydratorFactory::class,
        ],
    ]

];