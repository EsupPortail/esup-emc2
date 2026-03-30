<?php

namespace EmploiRepere;

use EmploiRepere\Controller\EmploiRepereCodeFonctionFicheMetierController;
use EmploiRepere\Controller\EmploiRepereCodeFonctionFicheMetierControllerFactory;
use EmploiRepere\Controller\EmploiRepereController;
use EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierForm;
use EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierFormFactory;
use EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierHydrator;
use EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierHydratorFactory;
use EmploiRepere\Provider\Privilege\EmploireperePrivileges;
use EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierService;
use EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EmploiRepereCodeFonctionFicheMetierController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'retirer',
                    ],
                    'privileges' => [
                        EmploireperePrivileges::EMPLOIREPERE_MODIFIER,
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
//                    'defaults' => [
//                        /** @see EmploiRepereController::indexAction() */
//                        'controller' => EmploiRepereController::class,
//                        'action' => 'index',
//                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'emploi-repere-code-fonction-fiche-metier' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/emploi-repere-code-fonction-fiche-metier',
                            'controller' => EmploiRepereCodeFonctionFicheMetierController::class,
//                            'action' => 'ajouter',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter/:emploi-repere',
                                    'defaults' => [
                                        /** @see EmploiRepereCodeFonctionFicheMetierController::ajouterAction() */
                                        'controller' => EmploiRepereCodeFonctionFicheMetierController::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:emploi-repere-code-fonction-fiche-metier',
                                    'defaults' => [
                                        /** @see EmploiRepereCodeFonctionFicheMetierController::modifierAction() */
                                        'controller' => EmploiRepereCodeFonctionFicheMetierController::class,
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'retirer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/retirer/:emploi-repere-code-fonction-fiche-metier',
                                    'defaults' => [
                                        /** @see EmploiRepereCodeFonctionFicheMetierController::retirerAction() */
                                        'controller' => EmploiRepereCodeFonctionFicheMetierController::class,
                                        'action' => 'retirer',
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
            EmploiRepereCodeFonctionFicheMetierService::class => EmploiRepereCodeFonctionFicheMetierServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            EmploiRepereCodeFonctionFicheMetierController::class => EmploiRepereCodeFonctionFicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            EmploiRepereCodeFonctionFicheMetierForm::class => EmploiRepereCodeFonctionFicheMetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EmploiRepereCodeFonctionFicheMetierHydrator::class => EmploiRepereCodeFonctionFicheMetierHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
        ],
        'aliases' => [
        ],
    ],
];