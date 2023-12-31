<?php

namespace Element;

use Element\Controller\CompetenceTypeController;
use Element\Controller\CompetenceTypeControllerFactory;
use Element\Form\CompetenceType\CompetenceTypeForm;
use Element\Form\CompetenceType\CompetenceTypeFormFactory;
use Element\Form\CompetenceType\CompetenceTypeHydrator;
use Element\Form\CompetenceType\CompetenceTypeHydratorFactory;
use Element\Provider\Privilege\CompetencetypePrivileges;
use Element\Service\CompetenceType\CompetenceTypeService;
use Element\Service\CompetenceType\CompetenceTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencetypePrivileges::COMPETENCETYPE_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceTypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CompetencetypePrivileges::COMPETENCETYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CompetenceTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CompetencetypePrivileges::COMPETENCETYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CompetenceTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CompetencetypePrivileges::COMPETENCETYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CompetenceTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CompetencetypePrivileges::COMPETENCETYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => CompetenceTypeController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        CompetencetypePrivileges::COMPETENCETYPE_EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'competence-type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence-type',
                            'defaults' => [
                                /** @see CompetenceTypeController::indexAction() */
                                'controller' => CompetenceTypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:competence-type',
                                    'defaults' => [
                                        /** @see CompetenceTypeController::afficherAction() */
                                        'controller' => CompetenceTypeController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see CompetenceTypeController::ajouterAction() */
                                        'controller' => CompetenceTypeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:competence-type',
                                    'defaults' => [
                                        /** @see CompetenceTypeController::modifierAction() */
                                        'controller' => CompetenceTypeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:competence-type',
                                    'defaults' => [
                                        /** @see CompetenceTypeController::historiserAction() */
                                        'controller' => CompetenceTypeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:competence-type',
                                    'defaults' => [
                                        /** @see CompetenceTypeController::restaurerAction() */
                                        'controller' => CompetenceTypeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:competence-type',
                                    'defaults' => [
                                        /** @see CompetenceTypeController::detruireAction() */
                                        'controller' => CompetenceTypeController::class,
                                        'action' => 'detruire',
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
            CompetenceTypeService::class => CompetenceTypeServiceFactory::class
        ],
    ],
    'controllers'     => [
        'factories' => [
            CompetenceTypeController::class => CompetenceTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceTypeForm::class => CompetenceTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceTypeHydrator::class => CompetenceTypeHydratorFactory::class
        ],
    ]

];