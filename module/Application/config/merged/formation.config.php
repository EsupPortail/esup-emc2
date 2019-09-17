<?php

namespace Application;

use Application\Controller\FormationController;
use Application\Controller\FormationControllerFactory;
use Application\Form\Formation\FormationForm;
use Application\Form\Formation\FormationFormFactory;
use Application\Form\Formation\FormationHydrator;
use Application\Form\Formation\FormationHydratorFactory;
use Application\Form\FormationTheme\FormationThemeForm;
use Application\Form\FormationTheme\FormationThemeFormFactory;
use Application\Form\FormationTheme\FormationThemeHydrator;
use Application\Form\FormationTheme\FormationThemeHydratorFactory;
use Application\Provider\Privilege\FormationPrivileges;
use Application\Service\Formation\FormationService;
use Application\Service\Formation\FormationServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'index',
                        'afficher',

                        'afficher-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'ajouter',
                        'editer',
                        'historiser',
                        'restaurer',

                        'ajouter-theme',
                        'editer-theme',
                        'historiser-theme',
                        'restaurer-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'detruire',
                        'detruire-theme',
                    ],
                    'privileges' => [
                        FormationPrivileges::DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-theme' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-theme',
                    'defaults' => [
                        'controller' => FormationController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'afficher-theme',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter-theme',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'editer-theme',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'historiser-theme',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'restaurer-theme',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation-theme',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'detruire-theme',
                            ],
                        ],
                    ],
                ],
            ],
            'formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation',
                    'defaults' => [
                        'controller' => FormationController::class,
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
                                'controller' => FormationController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationService::class => FormationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationController::class => FormationControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationForm::class => FormationFormFactory::class,
            FormationThemeForm::class => FormationThemeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationHydrator::class => FormationHydratorFactory::class,
            FormationThemeHydrator::class => FormationThemeHydratorFactory::class,
        ],
    ]

];