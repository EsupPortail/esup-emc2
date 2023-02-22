<?php

namespace FichePoste;

use FicheMetier\Controller\FicheMetierController;
use FicheMetier\Controller\FicheMetierControllerFactory;
use FicheMetier\Form\Raison\RaisonForm;
use FicheMetier\Form\Raison\RaisonFormFactory;
use FicheMetier\Form\Raison\RaisonHydrator;
use FicheMetier\Form\Raison\RaisonHydratorFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'modifier-metier',
                        'modifier-raison',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::historiserAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::restaurerAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'modifier-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-metier/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierMetierAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-metier',
                            ],
                        ],
                    ],
                    'modifier-raison' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-raison/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierRaisonAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'modifier-raison',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            RaisonForm::class => RaisonFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            RaisonHydrator::class => RaisonHydratorFactory::class,
        ],
    ]

];