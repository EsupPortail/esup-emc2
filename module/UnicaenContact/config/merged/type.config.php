<?php

namespace UnicaeContact;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenContact\Controller\TypeController;
use UnicaenContact\Controller\TypeControllerFactory;
use UnicaenContact\Form\Type\TypeForm;
use UnicaenContact\Form\Type\TypeFormFactory;
use UnicaenContact\Form\Type\TypeHydrator;
use UnicaenContact\Form\Type\TypeHydratorFactory;
use UnicaenContact\Provider\Privilege\ContacttypePrivileges;
use UnicaenContact\Service\Type\TypeService;
use UnicaenContact\Service\Type\TypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        ContacttypePrivileges::CONTACTYPE_INDEX,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ContacttypePrivileges::CONTACTTYPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ContacttypePrivileges::CONTACTTYPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ContacttypePrivileges::CONTACTTYPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ContacttypePrivileges::CONTACTTYPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => TypeController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ContacttypePrivileges::CONTACTTYPE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unicaen-contact' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/unicaen-contact',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'type' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/type',
                            'defaults' => [
                                /** @see TypeController::indexAction(); */
                                'controller' => TypeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:contact-type',
                                    'defaults' => [
                                        /** @see TypeController::afficherAction(); */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see TypeController::ajouterAction(); */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:contact-type',
                                    'defaults' => [
                                        /** @see TypeController::modifierAction(); */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:contact-type',
                                    'defaults' => [
                                        /** @see TypeController::historiserAction(); */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:contact-type',
                                    'defaults' => [
                                        /** @see TypeController::restaurerAction(); */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:contact-type',
                                    'defaults' => [
                                        /** @see TypeController::supprimerAction(); */
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

    'service_manager' => [
        'factories' => [
            TypeService::class => TypeServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            TypeController::class => TypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            TypeForm::class => TypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            TypeHydrator::class => TypeHydratorFactory::class,
        ],
    ],

];