<?php

namespace Carriere;

use Carriere\Controller\NiveauController;
use Carriere\Controller\NiveauControllerFactory;
use Carriere\Form\Niveau\NiveauForm;
use Carriere\Form\Niveau\NiveauFormFactory;
use Carriere\Form\Niveau\NiveauHydrator;
use Carriere\Form\Niveau\NiveauHydratorFactory;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormFactory;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeHydrator;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeHydratorFactory;
use Carriere\Provider\Privilege\NiveaucarrierePrivileges;
use Carriere\Service\Niveau\NiveauService;
use Carriere\Service\Niveau\NiveauServiceFactory;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceFactory;
use Carriere\View\Helper\NiveauEnveloppeViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        NiveaucarrierePrivileges::NIVEAUCARRIERE_INDEX,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        NiveaucarrierePrivileges::NIVEAUCARRIERE_AJOUTER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        NiveaucarrierePrivileges::NIVEAUCARRIERE_MODIFIER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        NiveaucarrierePrivileges::NIVEAUCARRIERE_HISTORISER,
                    ],
                ],
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        NiveaucarrierePrivileges::NIVEAUCARRIERE_SUPPRIMER,
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
                            'niveau' => [
                                'label'    => 'Niveaux de carrière',
                                'route'    => 'niveau',
                                'resource' => PrivilegeController::getResourceId(NiveauController::class, 'index') ,
                                'order'    => 840,
                                'pages' => [],
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
            'niveau' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/niveau',
                    'defaults' => [
                        /** @see NiveauController::indexAction() */
                        'controller' => NiveauController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see NiveauController::ajouterAction() */
                                'controller' => NiveauController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:niveau',
                            'defaults' => [
                                /** @see NiveauController::modifierAction() */
                                'controller' => NiveauController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:niveau',
                            'defaults' => [
                                /** @see NiveauController::historiserAction() */
                                'controller' => NiveauController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:niveau',
                            'defaults' => [
                                /** @see NiveauController::restaurerAction() */
                                'controller' => NiveauController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:niveau',
                            'defaults' => [
                                /** @see NiveauController::supprimerAction() */
                                'controller' => NiveauController::class,
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
            NiveauService::class => NiveauServiceFactory::class,
            NiveauEnveloppeService::class => NiveauEnveloppeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            NiveauController::class => NiveauControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            NiveauForm::class => NiveauFormFactory::class,
            NiveauEnveloppeForm::class => NiveauEnveloppeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            NiveauHydrator::class => NiveauHydratorFactory::class,
            NiveauEnveloppeHydrator::class => NiveauEnveloppeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'niveauEnveloppe' => NiveauEnveloppeViewHelper::class,
        ],
    ],
];