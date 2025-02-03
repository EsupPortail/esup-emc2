<?php

namespace UnicaeContact;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenContact\Controller\ContactController;
use UnicaenContact\Controller\ContactControllerFactory;
use UnicaenContact\Form\Contact\ContactForm;
use UnicaenContact\Form\Contact\ContactFormFactory;
use UnicaenContact\Form\Contact\ContactHydrator;
use UnicaenContact\Form\Contact\ContactHydratorFactory;
use UnicaenContact\Provider\Privilege\ContactPrivileges;
use UnicaenContact\Service\Contact\ContactService;
use UnicaenContact\Service\Contact\ContactServiceFactory;
use UnicaenContact\View\Helper\ContactViewHelper;
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
                    'privileges' => [
                        ContactPrivileges::CONTACT_INDEX,
                    ],
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ContactPrivileges::CONTACT_AFFICHER,
                    ],
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        ContactPrivileges::CONTACT_AJOUTER,
                    ],
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        ContactPrivileges::CONTACT_MODIFIER,
                    ],
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ContactPrivileges::CONTACT_HISTORISER,
                    ],
                ],
                [
                    'controller' => ContactController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ContactPrivileges::CONTACT_SUPPRIMER,
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
                    'contact' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contact',
                            'defaults' => [
                                /** @see ContactController::indexAction(); */
                                'controller' => ContactController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:contact',
                                    'defaults' => [
                                        /** @see ContactController::afficherAction(); */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see ContactController::ajouterAction(); */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:contact',
                                    'defaults' => [
                                        /** @see ContactController::modifierAction(); */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:contact',
                                    'defaults' => [
                                        /** @see ContactController::historiserAction(); */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:contact',
                                    'defaults' => [
                                        /** @see ContactController::restaurerAction(); */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:contact',
                                    'defaults' => [
                                        /** @see ContactController::supprimerAction(); */
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
            ContactService::class => ContactServiceFactory::class,
        ],
    ],
    'controllers'     => [
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
    'view_helpers' => [
        'invokables' => [
            'contact' => ContactViewHelper::class,
        ],
    ],

];