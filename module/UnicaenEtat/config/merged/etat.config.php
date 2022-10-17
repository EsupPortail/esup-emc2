<?php

namespace UnicaenEtat;

use UnicaenEtat\Controller\EtatController;
use UnicaenEtat\Controller\EtatControllerFactory;
use UnicaenEtat\Form\Etat\EtatForm;
use UnicaenEtat\Form\Etat\EtatFormFactory;
use UnicaenEtat\Form\Etat\EtatHydrator;
use UnicaenEtat\Form\Etat\EtatHydratorFactory;
use UnicaenEtat\Form\EtatFieldset\EtatFieldset;
use UnicaenEtat\Form\EtatFieldset\EtatFieldsetFactory;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormFactory;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatHydrator;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatHydratorFactory;
use UnicaenEtat\Provider\Privilege\EtatPrivileges;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\Etat\EtatServiceFactory;
use UnicaenEtat\View\Helper\EtatBadgeViewHelper;
use UnicaenEtat\View\Helper\EtatBadgeViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EtatController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_INDEX,
                    ],
                ],
                [
                    'controller' => EtatController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_AJOUTER,
                    ],
                ],
                [
                    'controller' => EtatController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_MODIFIER,
                    ],
                ],
                [
                    'controller' => EtatController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        EtatPrivileges::ETAT_HISTORISER,
                    ],
                ],
                [
                    'controller' => EtatController::class,
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
//                            'unicaen-etat' => [
//                                'pages' => [
//                                    'etat' => [
//                                        'label' => 'État',
//                                        'route' => 'unicaen-etat/etat',
//                                        'resource' => PrivilegeController::getResourceId(EtatController::class, 'index'),
//                                        'order'    => 10001,
//                                    ],
//                                ],
//                            ],
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
                    'etat' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/etat',
                            'defaults' => [
                                'controller' => EtatController::class,
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
                                        'controller' => EtatController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:etat',
                                    'defaults' => [
                                        'controller' => EtatController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:etat',
                                    'defaults' => [
                                        'controller' => EtatController::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:etat',
                                    'defaults' => [
                                        'controller' => EtatController::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:etat',
                                    'defaults' => [
                                        'controller' => EtatController::class,
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
            EtatService::class => EtatServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            EtatController::class => EtatControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            EtatFieldset::class => EtatFieldsetFactory::class,
            EtatForm::class => EtatFormFactory::class,
            SelectionEtatForm::class => SelectionEtatFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => array(
            EtatHydrator::class => EtatHydratorFactory::class,
            SelectionEtatHydrator::class => SelectionEtatHydratorFactory::class,
        ),
    ],
    'view_helpers' => [
        'factories' => [
            EtatBadgeViewHelper::class => EtatBadgeViewHelperFactory::class,
        ],
        'aliases' => [
            'etatbadge' => EtatBadgeViewHelper::class,
        ],
    ],

];