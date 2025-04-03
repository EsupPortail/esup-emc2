<?php

namespace Application;

use Application\Controller\SpecificiteController;
use Application\Controller\SpecificiteControllerFactory;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\View\Helper\SpecificiteActiviteViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SpecificiteController::class,
                    'action' => [
                        'ajouter-activite',
                        'retirer-activite',
                        'gerer-activite',
                    ],
                    'privileges' => [
                        FichePostePrivileges::FICHEPOSTE_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'specificite' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/specificite',
                    'defaults' => [
                        'controller' => SpecificiteController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-activite/:fiche-poste[/:specificite-poste]',
                            'defaults' => [
                                /** @see SpecificiteController::ajouterActiviteAction() */
                                'action'     => 'ajouter-activite',
                            ],
                        ],
                    ],
                    'retirer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-activite/:specificite-activite',
                            'defaults' => [
                                'controller' => SpecificiteController::class,
                                'action'     => 'retirer-activite',
                            ],
                        ],
                    ],
                    'gerer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-activite/:specificite-activite',
                            'defaults' => [
                                'controller' => SpecificiteController::class,
                                'action'     => 'gerer-activite',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            SpecificiteController::class => SpecificiteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ],
    'view_helpers' => [
        'invokables' => [
            'specificiteActivite' => SpecificiteActiviteViewHelper::class,
        ],
    ],
];