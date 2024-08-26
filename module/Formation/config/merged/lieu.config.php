<?php

namespace Formation;

use Formation\Controller\LieuController;
use Formation\Controller\LieuControllerFactory;
use Formation\Form\Lieu\LieuForm;
use Formation\Form\Lieu\LieuFormFactory;
use Formation\Form\Lieu\LieuHydrator;
use Formation\Form\Lieu\LieuHydratorFactory;
use Formation\Form\Validator\LieuUtilise\LieuUtiliseValidator;
use Formation\Form\Validator\LieuUtilise\LieuUtiliseValidatorFactory;
use Formation\Provider\Privilege\FormationlieuPrivileges;
use Formation\Service\Lieu\LieuService;
use Formation\Service\Lieu\LieuServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => LieuController::class,
                    'action' => [
                        'index',
                        'rechercher',
                    ],
                    'privileges' => [
                        FormationlieuPrivileges::FORMATIONLIEU_INDEX,
                    ],
                ],
                [
                    'controller' => LieuController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormationlieuPrivileges::FORMATIONLIEU_AFFICHER,
                    ],
                ],
                [
                    'controller' => LieuController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationlieuPrivileges::FORMATIONLIEU_AJOUTER,
                    ],
                ],
                [
                    'controller' => LieuController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        FormationlieuPrivileges::FORMATIONLIEU_MODIFIER,
                    ],
                ],
                [
                    'controller' => LieuController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FormationlieuPrivileges::FORMATIONLIEU_HISTORISER,
                    ],
                ],
                [
                    'controller' => LieuController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationlieuPrivileges::FORMATIONLIEU_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'lieu' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/lieu',
                            'defaults' => [
                                /** @see LieuController::indexAction() */
                                'controller' => LieuController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:lieu',
                                    'defaults' => [
                                        /** @see LieuController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'rechercher' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher',
                                    'defaults' => [
                                        /** @see LieuController::rechercherAction() */
                                        'action' => 'rechercher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see LieuController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:lieu',
                                    'defaults' => [
                                        /** @see LieuController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:lieu',
                                    'defaults' => [
                                        /** @see LieuController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:lieu',
                                    'defaults' => [
                                        /** @see LieuController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:lieu',
                                    'defaults' => [
                                        /** @see LieuController::supprimerAction() */
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'lieu' => [
                                'label' => 'Lieux',
                                'route' => 'formation/lieu',
                                'resource' => PrivilegeController::getResourceId(LieuController::class, 'index'),
                                'order' => 226,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'service_manager' => [
        'factories' => [
            LieuService::class => LieuServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            LieuController::class => LieuControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            LieuForm::class => LieuFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            LieuHydrator::class => LieuHydratorFactory::class,
        ],
    ],
    'validators' => [
        'factories' => [
            LieuUtiliseValidator::class => LieuUtiliseValidatorFactory::class,
        ],
    ],

];