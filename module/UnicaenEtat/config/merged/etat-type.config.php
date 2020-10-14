<?php

namespace UnicaenEtat;

use UnicaenEtat\View\Helper\EtatTypeViewHelper;
use UnicaenEtat\Controller\EtatTypeController;
use UnicaenEtat\Controller\EtatTypeControllerFactory;
use UnicaenEtat\Form\EtatType\EtatTypeForm;
use UnicaenEtat\Form\EtatType\EtatTypeFormFactory;
use UnicaenEtat\Form\EtatType\EtatTypeHydrator;
use UnicaenEtat\Form\EtatType\EtatTypeHydratorFactory;
use UnicaenEtat\Provider\Privilege\EtatPrivileges;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenEtat\Service\EtatType\EtatTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EtatTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_INDEX,
                    ],
                ],
                [
                    'controller' => EtatTypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_AJOUTER,
                    ],
                ],
                [
                    'controller' => EtatTypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_MODIFIER,
                    ],
                ],
                [
                    'controller' => EtatTypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_HISTORISER,
                    ],
                ],
                [
                    'controller' => EtatTypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaen-etat' => [
                                'pages' => [
                                    'etat-type' => [
                                        'label' => 'État type',
                                        'route' => 'unicaen-etat/etat-type',
                                        'resource' => PrivilegeController::getResourceId(EtatTypeController::class, 'index'),
                                        'order'    => 10001,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unicaen-etat' => [
                'child_routes' => [
                    'etat-type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/etat-type',
                            'defaults' => [
                                'controller' => EtatTypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        'controller' => EtatTypeController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:etat-type',
                                    'defaults' => [
                                        'controller' => EtatTypeController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:etat-type',
                                    'defaults' => [
                                        'controller' => EtatTypeController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:etat-type',
                                    'defaults' => [
                                        'controller' => EtatTypeController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:etat-type',
                                    'defaults' => [
                                        'controller' => EtatTypeController::class,
                                        'action' => 'supprimer',
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
            EtatTypeService::class => EtatTypeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            EtatTypeController::class => EtatTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            EtatTypeForm::class => EtatTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EtatTypeHydrator::class => EtatTypeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'etattype'  => EtatTypeViewHelper::class,
        ],
    ],


];