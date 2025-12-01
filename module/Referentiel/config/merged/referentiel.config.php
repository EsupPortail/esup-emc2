<?php

namespace Referentiel;

use Referentiel\Controller\ReferentielController;
use Referentiel\Controller\ReferentielControllerFactory;
use Referentiel\Form\Referentiel\ReferentielForm;
use Referentiel\Form\Referentiel\ReferentielFormFactory;
use Referentiel\Form\Referentiel\ReferentielHydrator;
use Referentiel\Form\Referentiel\ReferentielHydratorFactory;
use Referentiel\Provider\Privilege\ReferentielPrivileges;
use Referentiel\Service\Referentiel\ReferentielService;
use Referentiel\Service\Referentiel\ReferentielServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ReferentielPrivileges::REFERENTIEL_INDEX,
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ReferentielPrivileges::REFERENTIEL_AFFICHER,
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ReferentielPrivileges::REFERENTIEL_AJOUTER,
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ReferentielPrivileges::REFERENTIEL_MODIFIER,
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ReferentielPrivileges::REFERENTIEL_HISTORISER,
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ReferentielPrivileges::REFERENTIEL_SUPPRIMER,
                    ],
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
                            'referentiel' => [
                                'label' => 'Référentiels',
                                'route' => 'referentiel',
                                'resource' => PrivilegeController::getResourceId(ReferentielController::class, 'index'),
                                'order'    => 10000,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'referentiel' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/referentiel',
                    'defaults' => [
                        /** @see ReferentielController::indexAction() */
                        'controller' => ReferentielController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:referentiel',
                            'defaults' => [
                                /** @see ReferentielController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see ReferentielController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:referentiel',
                            'defaults' => [
                                /** @see ReferentielController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:referentiel',
                            'defaults' => [
                                /** @see ReferentielController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:referentiel',
                            'defaults' => [
                                /** @see ReferentielController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:referentiel',
                            'defaults' => [
                                /** @see ReferentielController::detruireAction() */
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ReferentielService::class => ReferentielServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ReferentielController::class => ReferentielControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ReferentielForm::class => ReferentielFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ReferentielHydrator::class => ReferentielHydratorFactory::class,
        ],
    ]

];