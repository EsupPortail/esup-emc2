<?php

namespace Structure;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Structure\Controller\ContactController;
use Structure\Controller\ContactControllerFactory;
use Structure\Form\Contact\ContactForm;
use Structure\Form\Contact\ContactFormFactory;
use Structure\Form\Contact\ContactHydrator;
use Structure\Form\Contact\ContactHydratorFactory;
use UnicaenContact\Provider\Privilege\ContactPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => ContactPrivileges::CONTACT_INDEX,
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => ContactPrivileges::CONTACT_AJOUTER,
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => ContactPrivileges::CONTACT_MODIFIER,
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => ContactPrivileges::CONTACT_SUPPRIMER,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'structure' => [
                'child_routes' => [
                    'contact' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contact',
                            'defaults' => [
                                /** @see ContactController::indexAction() */
                                'controller' => ContactController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:structure]',
                                    'defaults' => [
                                        /** @see ContactController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:contact',
                                    'defaults' => [
                                        /** @see ContactController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:contact',
                                    'defaults' => [
                                        /** @see ContactController::supprimerAction() */
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
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'contact' => [
                                'label' => 'Contacts',
                                'route' => 'structure/contact',
                                'resource' => PrivilegeController::getResourceId(ContactController::class, 'index'),
                                'order' => 100001,
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
        ],
    ],
    'controllers' => [
        'factories' => [
            ContactController::class => ContactControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ContactForm::class => ContactFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ContactHydrator::class => ContactHydratorFactory::class,
        ],
    ],
];