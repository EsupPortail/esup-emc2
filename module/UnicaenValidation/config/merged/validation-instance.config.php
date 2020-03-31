<?php

namespace UnicaenValidation;

use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenValidation\Controller\ValidationInstanceController;
use UnicaenValidation\Controller\ValidationInstanceControllerFactory;
use UnicaenValidation\Form\ValidationInstance\ValidationInstanceForm;
use UnicaenValidation\Form\ValidationInstance\ValidationInstanceFormFactory;
use UnicaenValidation\Form\ValidationInstance\ValidationInstanceHydrator;
use UnicaenValidation\Form\ValidationInstance\ValidationInstanceHydratorFactory;
use UnicaenValidation\Provider\Privilege\ValidationinstancePrivileges;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceFactory;
use UnicaenValidation\View\Helper\ValidationAfficherViewHelper;
use UnicaenValidation\View\Helper\ValidationValiderViewHelperFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ValidationInstanceController::class,
                    'action' => [
                        'index',
                        'valider',
                        'refuser',
                    ],
                    'privileges' => [
                        ValidationinstancePrivileges::VALIDATIONINSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => ValidationInstanceController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                    ],
                    'privileges' => [
                        ValidationinstancePrivileges::VALIDATIONINSTANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => ValidationInstanceController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        ValidationinstancePrivileges::VALIDATIONINSTANCE_HISTORISER,
                    ],
                ],
                [
                    'controller' => ValidationInstanceController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        ValidationinstancePrivileges::VALIDATIONINSTANCE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'validation' => [
                'child_routes' => [
                    'instance' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/instance',
                            'defaults' => [
                                'controller' => ValidationInstanceController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:validation',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:validation',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:validation',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/detruire/:validation',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'detruire',
                                    ],
                                ],
                            ],

                            'valider' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/valider/:type/:entityclass/:entityid',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'valider',
                                    ],
                                ],
                            ],
                            'refuser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route'    => '/refuser/:type/:entityclass/:entityid',
                                    'defaults' => [
                                        'controller' => ValidationInstanceController::class,
                                        'action'     => 'refuser',
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
            ValidationInstanceService::class => ValidationInstanceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ValidationInstanceController::class => ValidationInstanceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ValidationInstanceForm::class => ValidationInstanceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ValidationInstanceHydrator::class => ValidationInstanceHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'validationAfficher'              => ValidationAfficherViewHelper::class,
        ],
        'factories' => [
            'validationValider'              => ValidationValiderViewHelperFactory::class,
        ]
    ]

];