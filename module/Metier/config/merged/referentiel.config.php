<?php

namespace Metier;

use Metier\Controller\ReferentielController;
use Metier\Controller\ReferentielControllerFactory;
use Metier\Form\Referentiel\ReferentielForm;
use Metier\Form\Referentiel\ReferentielFormFactory;
use Metier\Form\Referentiel\ReferentielHydrator;
use Metier\Form\Referentiel\ReferentielHydratorFactory;
use Metier\Provider\Privilege\ReferentielmetierPrivileges;
use Metier\Service\Referentiel\ReferentielService;
use Metier\Service\Referentiel\ReferentielServiceFactory;
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
                        'index'
                    ],
                    'privileges' => [
                        ReferentielmetierPrivileges::REFERENTIEL_INDEX
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ReferentielmetierPrivileges::REFERENTIEL_AJOUTER
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ReferentielmetierPrivileges::REFERENTIEL_MODIFIER
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ReferentielmetierPrivileges::REFERENTIEL_HISTORISER
                    ],
                ],
                [
                    'controller' => ReferentielController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ReferentielmetierPrivileges::REFERENTIEL_SUPPRIMER
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'metier' => [
                'child_routes' => [
                    'referentiel' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route' => '/referentiel',
                            'defaults' => [
                                /** @see ReferentielController::indexAction() */
                                'controller' => ReferentielController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate'=> true,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        /** @see ReferentielController::ajouterAction() */
                                        'controller' => ReferentielController::class,
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
                                        'controller' => ReferentielController::class,
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
                                        'controller' => ReferentielController::class,
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
                                        'controller' => ReferentielController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:referentiel',
                                    'defaults' => [
                                        /** @see ReferentielController::supprimerAction() */
                                        'controller' => ReferentielController::class,
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