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
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        ],
                        'may_terminate'=> false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
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