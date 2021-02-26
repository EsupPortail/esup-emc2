<?php

namespace UnicaenParametre;

use UnicaenParametre\Controller\ParametreController;
use UnicaenParametre\Controller\ParametreControllerFactory;
use UnicaenParametre\Form\Parametre\ParametreForm;
use UnicaenParametre\Form\Parametre\ParametreFormFactory;
use UnicaenParametre\Provider\Privilege\ParametrePrivileges;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenParametre\Service\Parametre\ParametreServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ParametreController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'pivileges' => ParametrePrivileges::PARAMETRE_AJOUTER,
                ],
                [
                    'controller' => ParametreController::class,
                    'action' => [
                        'modifier',
                    ],
                    'pivileges' => ParametrePrivileges::PARAMETRE_MODIFIER,
                ],
                [
                    'controller' => ParametreController::class,
                    'action' => [
                        'modifier-valeur',
                    ],
                    'pivileges' => ParametrePrivileges::PARAMETRE_VALEUR,
                ],
                [
                    'controller' => ParametreController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'pivileges' => ParametrePrivileges::PARAMETRE_SUPPRIMER,
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'parametre' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/parametre',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter/:categorie',
                            'defaults' => [
                                'controller' => ParametreController::class,
                                'action' => 'ajouter'
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:parametre',
                            'defaults' => [
                                'controller' => ParametreController::class,
                                'action' => 'modifier'
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:parametre',
                            'defaults' => [
                                'controller' => ParametreController::class,
                                'action' => 'supprimer'
                            ],
                        ],
                    ],
                    'modifier-valeur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-valeur/:parametre',
                            'defaults' => [
                                'controller' => ParametreController::class,
                                'action' => 'modifier-valeur'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ParametreService::class => ParametreServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ParametreController::class => ParametreControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ParametreForm::class => ParametreFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ParametreService::class => ParametreServiceFactory::class,
        ],
    ]

];