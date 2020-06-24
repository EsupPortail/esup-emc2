<?php

use Mailing\Controller\MailingController;
use Mailing\Controller\MailTypeController;
use Mailing\Controller\MailTypeControllerFactory;
use Mailing\Form\MailContent\MailContentForm;
use Mailing\Form\MailContent\MailContentFormFactory;
use Mailing\Form\MailType\MailTypeForm;
use Mailing\Form\MailType\MailTypeFormFactory;
use Mailing\Form\MailType\MailTypeHydrator;
use Mailing\Form\MailType\MailTypeHydratorFactory;
use Mailing\Provider\Privilege\MailingPrivileges;
use Mailing\Service\MailType\MailTypeService;
use Mailing\Service\MailType\MailTypeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return array(
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MailTypeController::class,
                    'action'     => [
                        'ajouter',
                        'afficher',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'modifier-mail',
                    ],
                    'privileges' => [
                        MailingPrivileges::MAILING_EFFACER,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'mailing' => [
                'type' => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/mailing',
                    'defaults' => [
                        'controller' => MailingController::class,
                        'action'     => 'index',
                    ],
                ],
                'child_routes'  => [
                    'type' => [
                        'type' => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/type',
                        ],
                        'child_routes'  => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher/:mail-type',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:mail-type',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:mail-type',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:mail-type',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire/:mail-type',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'detruire',
                                    ],
                                ],
                            ],
                            'modifier-mail' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier-mail/:mail-type',
                                    'defaults' => [
                                        'controller' => MailTypeController::class,
                                        'action'     => 'modifier-mail',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'form_elements' => [
        'factories' => [
            MailContentForm::class => MailContentFormFactory::class,
            MailTypeForm::class => MailTypeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            MailTypeHydrator::class => MailTypeHydratorFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            MailTypeService::class => MailTypeServiceFactory::class,
        ],

    ],
    'controllers' => [
        'factories' => [
            MailTypeController::class => MailTypeControllerFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
);
