<?php

namespace Element;

use Element\Controller\CompetenceDisciplineController;
use Element\Controller\CompetenceDisciplineControllerFactory;
use Element\Form\CompetenceDiscipline\CompetenceDisciplineForm;
use Element\Form\CompetenceDiscipline\CompetenceDisciplineFormFactory;
use Element\Form\CompetenceDiscipline\CompetenceDisciplineHydrator;
use Element\Form\CompetenceDiscipline\CompetenceDisciplineHydratorFactory;
use Element\Provider\Privilege\CompetencedisciplinePrivileges;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineService;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceDisciplineController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencedisciplinePrivileges::COMPETENCEDISCIPLINE_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceDisciplineController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CompetencedisciplinePrivileges::COMPETENCEDISCIPLINE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CompetenceDisciplineController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CompetencedisciplinePrivileges::COMPETENCEDISCIPLINE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CompetenceDisciplineController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CompetencedisciplinePrivileges::COMPETENCEDISCIPLINE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CompetenceDisciplineController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CompetencedisciplinePrivileges::COMPETENCEDISCIPLINE_HISTORISER,
                    ],
                ],
                [
                    'controller' => CompetenceDisciplineController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        CompetencedisciplinePrivileges::COMPETENCEDISCIPLINE_SUPPRIMER,
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
                    'competence-discipline' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence-discipline',
                            'defaults' => [
                                /** @see CompetenceDisciplineController::indexAction() */
                                'controller' => CompetenceDisciplineController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:competence-discipline',
                                    'defaults' => [
                                        /** @see CompetenceDisciplineController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see CompetenceDisciplineController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:competence-discipline',
                                    'defaults' => [
                                        /** @see CompetenceDisciplineController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:competence-discipline',
                                    'defaults' => [
                                        /** @see CompetenceDisciplineController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:competence-discipline',
                                    'defaults' => [
                                        /** @see CompetenceDisciplineController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detruire/:competence-discipline',
                                    'defaults' => [
                                        /** @see CompetenceDisciplineController::detruireAction() */
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
            CompetenceDisciplineService::class => CompetenceDisciplineServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CompetenceDisciplineController::class => CompetenceDisciplineControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceDisciplineForm::class => CompetenceDisciplineFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceDisciplineHydrator::class => CompetenceDisciplineHydratorFactory::class,
        ],
    ],

];