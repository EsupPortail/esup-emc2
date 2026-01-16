<?php

namespace EmploiRepere;

use EmploiRepere\Controller\EmploiRepereController;
use EmploiRepere\Controller\EmploiRepereControllerFactory;
use EmploiRepere\Form\EmploiRepere\EmploiRepereForm;
use EmploiRepere\Form\EmploiRepere\EmploiRepereFormFactory;
use EmploiRepere\Form\EmploiRepere\EmploiRepereHydrator;
use EmploiRepere\Form\EmploiRepere\EmploiReperehydratorFactory;
use EmploiRepere\Provider\Privilege\EmploireperePrivileges;
use EmploiRepere\Service\EmploiRepere\EmploiRepereService;
use EmploiRepere\Service\EmploiRepere\EmploiRepereServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EmploiRepereController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_INDEX,
                    ],
                ],
                [
                    'controller' => EmploiRepereController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_AJOUTER,
                    ],
                ],
                [
                    'controller' => EmploiRepereController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_AFFICHER,
                    ],
                ],
                [
                    'controller' => EmploiRepereController::class,
                    'action' => [
                        'modifier',
                        'modifier-bis',
                        'update-libelle'
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_MODIFIER,
                    ],
                ],
                [
                    'controller' => EmploiRepereController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_MODIFIER,
                    ],
                ],
                [
                    'controller' => EmploiRepereController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'emplois-reperes' => [
                                'label' => 'Emplois-repères',
                                'route' => 'emploi-repere',
                                'resource' => PrivilegeController::getResourceId(EmploiRepereController::class, 'index'),
                                'order'    => 2044,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'emploi-repere' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/emploi-repere',
                    'defaults' => [
                        /** @see EmploiRepereController::indexAction() */
                        'controller' => EmploiRepereController::class,
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
                                /** @see EmploiRepereController::ajouterAction() */
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:emploi-repere',
                            'defaults' => [
                                /** @see EmploiRepereController::afficherAction() */
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:emploi-repere',
                            'defaults' => [
                                /** @see EmploiRepereController::modifierAction() */
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:emploi-repere',
                            'defaults' => [
                                /** @see EmploiRepereController::historiserAction() */
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:emploi-repere',
                            'defaults' => [
                                /** @see EmploiRepereController::restaurerAction() */
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:emploi-repere',
                            'defaults' => [
                                /** @see EmploiRepereController::supprimerAction() */
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-bis' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-bis/:emploi-repere',
                            'defaults' => [
                                /** @see EmploiRepereController::modifierBisAction() */
                                'action' => 'modifier-bis',
                            ],
                        ],
                    ],
                    'update-libelle' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/update-libelle[/:emploi-repere/:libelle]',
                            'defaults' => [
                                /** @see EmploiRepereController::updateLibelleAction() */
                                'action' => 'update-libelle',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            EmploiRepereService::class => EmploiRepereServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            EmploiRepereController::class => EmploiRepereControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            EmploiRepereForm::class => EmploiRepereFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EmploiRepereHydrator::class => EmploiRepereHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
        ],
        'aliases' => [
        ],
    ],
];