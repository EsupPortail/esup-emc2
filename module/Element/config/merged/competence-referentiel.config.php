<?php

namespace Element;

use Element\Controller\CompetenceReferentielController;
use Element\Controller\CompetenceReferentielControllerFactory;
use Element\Form\CompetenceReferentiel\CompetenceReferentielForm;
use Element\Form\CompetenceReferentiel\CompetenceReferentielFormFactory;
use Element\Form\CompetenceReferentiel\CompetenceReferentielHydrator;
use Element\Form\CompetenceReferentiel\CompetenceReferentielHydratorFactory;
use Element\Provider\Privilege\CompetencereferentielPrivileges;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceFactory;
use Element\View\Helper\CompetenceReferenceViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CompetenceReferentielController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CompetencereferentielPrivileges::COMPETENCEREFERENTIEL_INDEX,
                    ],
                ],
                [
                    'controller' => CompetenceReferentielController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CompetencereferentielPrivileges::COMPETENCEREFERENTIEL_AFFICHER,
                    ],
                ],
                [
                    'controller' => CompetenceReferentielController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CompetencereferentielPrivileges::COMPETENCEREFERENTIEL_AJOUTER,
                    ],
                ],
                [
                    'controller' => CompetenceReferentielController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CompetencereferentielPrivileges::COMPETENCEREFERENTIEL_MODIFIER,
                    ],
                ],
                [
                    'controller' => CompetenceReferentielController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CompetencereferentielPrivileges::COMPETENCEREFERENTIEL_HISTORISER,
                    ],
                ],
                [
                    'controller' => CompetenceReferentielController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        CompetencereferentielPrivileges::COMPETENCEREFERENTIEL_EFFACER,
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
                'may_terminate' => true,
                'child_routes' => [
                    'competence-referentiel' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/competence-referentiel',
                            'defaults' => [
                                /** @see CompetenceReferentielController::indexAction() */
                                'controller' => CompetenceReferentielController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:competence-referentiel',
                                    'defaults' => [
                                        /** @see CompetenceReferentielController::afficherAction() */
                                        'controller' => CompetenceReferentielController::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see CompetenceReferentielController::ajouterAction() */
                                        'controller' => CompetenceReferentielController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:competence-referentiel',
                                    'defaults' => [
                                        /** @see CompetenceReferentielController::modifierAction() */
                                        'controller' => CompetenceReferentielController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:competence-referentiel',
                                    'defaults' => [
                                        /** @see CompetenceReferentielController::historiserAction() */
                                        'controller' => CompetenceReferentielController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:competence-referentiel',
                                    'defaults' => [
                                        /** @see CompetenceReferentielController::restaurerAction() */
                                        'controller' => CompetenceReferentielController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:competence-referentiel',
                                    'defaults' => [
                                        /** @see CompetenceReferentielController::supprimerAction() */
                                        'controller' => CompetenceReferentielController::class,
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
            CompetenceReferentielService::class => CompetenceReferentielServiceFactory::class
        ],
    ],
    'controllers' => [
        'factories' => [
            CompetenceReferentielController::class => CompetenceReferentielControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CompetenceReferentielForm::class => CompetenceReferentielFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CompetenceReferentielHydrator::class => CompetenceReferentielHydratorFactory::class
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'competenceReference' => CompetenceReferenceViewHelper::class,
        ],
    ],
];