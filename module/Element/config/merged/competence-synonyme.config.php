<?php

namespace Element;

use Element\Controller\CompetenceSynonymeController;
use Element\Controller\CompetenceSynonymeControllerFactory;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeForm;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeFormFactory;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeHydrator;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeHydratorFactory;
use Element\Provider\Privilege\CompetencePrivileges;
use Element\Service\CompetenceSynonyme\CompetenceSynonymeService;
use Element\Service\CompetenceSynonyme\CompetenceSynonymeServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceSynonymeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceSynonymeController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'vider',
                        'supprimer',
                    ],
                    'privileges' => [
                        CompetencePrivileges::COMPETENCE_MODIFIER,
                    ],
                ],
            ],
        ],
    ],


    'router' => [
        'routes' => [
            'element' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/element',
                ],
                'child_routes' => [
                    'competence-synonyme' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence-synonyme',
                            'defaults' => [
                                /** @see CompetenceSynonymeController::indexAction() */
                                'controller' => CompetenceSynonymeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:competence]',
                                    'defaults' => [
                                        /** @see CompetenceSynonymeController::ajouterAction() */
                                        'controller' => CompetenceSynonymeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:competence-synonyme',
                                    'defaults' => [
                                        /** @see CompetenceSynonymeController::modifierAction() */
                                        'controller' => CompetenceSynonymeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'vider' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/vider/:competence',
                                    'defaults' => [
                                        /** @see CompetenceSynonymeController::viderAction() */
                                        'controller' => CompetenceSynonymeController::class,
                                        'action' => 'vider',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:competence-synonyme',
                                    'defaults' => [
                                        /** @see CompetenceSynonymeController::supprimerAction() */
                                        'controller' => CompetenceSynonymeController::class,
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
            CompetenceSynonymeService::class => CompetenceSynonymeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CompetenceSynonymeController::class => CompetenceSynonymeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceSynonymeForm::class => CompetenceSynonymeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceSynonymeHydrator::class => CompetenceSynonymeHydratorFactory::class,

        ],
    ],

];