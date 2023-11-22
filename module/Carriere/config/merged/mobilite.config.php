<?php

namespace Carriere;

use Carriere\Controller\MobiliteController;
use Carriere\Controller\MobiliteControllerFactory;
use Carriere\Form\Mobilite\MobiliteForm;
use Carriere\Form\Mobilite\MobiliteFormFactory;
use Carriere\Form\Mobilite\MobiliteHydrator;
use Carriere\Form\Mobilite\MobiliteHydratorFactory;
use Carriere\Provider\Privilege\MobilitePrivileges;
use Carriere\Service\Mobilite\MobiliteService;
use Carriere\Service\Mobilite\MobiliteServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MobiliteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        MobilitePrivileges::MOBILITE_INDEX
                    ],
                ],
                [
                    'controller' => MobiliteController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        MobilitePrivileges::MOBILITE_AFFICHER
                    ],
                ],
                [
                    'controller' => MobiliteController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MobilitePrivileges::MOBILITE_AJOUTER
                    ],
                ],
                [
                    'controller' => MobiliteController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        MobilitePrivileges::MOBILITE_MODIFIER
                    ],
                ],
                [
                    'controller' => MobiliteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MobilitePrivileges::MOBILITE_HISTORISER
                    ],
                ],
                [
                    'controller' => MobiliteController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        MobilitePrivileges::MOBILITE_SUPPRIMER
                    ],
                ],


            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'order' => 2110,
                                'label' => 'Mobilités',
                                'route' => 'mobilite',
                                'resource' => PrivilegeController::getResourceId(MobiliteController::class, 'index') ,
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
            'mobilite' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mobilite',
                    'defaults' => [
                        /** @see MobiliteController::indexAction(); */
                        'controller' => MobiliteController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:mobilite',
                            'defaults' => [
                                /** @see MobiliteController::afficherAction(); */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see MobiliteController::ajouterAction(); */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:mobilite',
                            'defaults' => [
                                /** @see MobiliteController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:mobilite',
                            'defaults' => [
                                /** @see MobiliteController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:mobilite',
                            'defaults' => [
                                /** @see MobiliteController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:mobilite',
                            'defaults' => [
                                /** @see MobiliteController::supprimerAction() */
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
            MobiliteService::class => MobiliteServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MobiliteController::class => MobiliteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            MobiliteForm::class => MobiliteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            MobiliteHydrator::class => MobiliteHydratorFactory::class,
        ],
    ]

];