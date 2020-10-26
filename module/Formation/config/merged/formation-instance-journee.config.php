<?php

namespace Formation;

use Formation\Controller\FormationInstanceJourneeController;
use Formation\Controller\FormationInstanceJourneeControllerFactory;
use Formation\Form\FormationJournee\FormationJourneeForm;
use Formation\Form\FormationJournee\FormationJourneeFormFactory;
use Formation\Form\FormationJournee\FormationJourneeHydrator;
use Formation\Form\FormationJournee\FormationJourneeHydratorFactory;
use Formation\Provider\Privilege\FormationinstancejourneePrivileges;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceJourneeController::class,
                    'action' => [
                        'ajouter-journee',
                        'modifier-journee',
                        'historiser-journee',
                        'restaurer-journee',
                        'supprimer-journee',
                    ],
                    'privileges' => [
                        FormationinstancejourneePrivileges::FORMATIONINSTANCEJOURNEE_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-instance' => [
                'child_routes' => [
                    'ajouter-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-journee/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceJourneeController::class,
                                'action'     => 'ajouter-journee',
                            ],
                        ],
                    ],
                    'modifier-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceJourneeController::class,
                                'action'     => 'modifier-journee',
                            ],
                        ],
                    ],
                    'historiser-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceJourneeController::class,
                                'action'     => 'historiser-journee',
                            ],
                        ],
                    ],
                    'restaurer-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceJourneeController::class,
                                'action'     => 'restaurer-journee',
                            ],
                        ],
                    ],
                    'supprimer-journee' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-journee/:journee',
                            'defaults' => [
                                'controller' => FormationInstanceJourneeController::class,
                                'action'     => 'supprimer-journee',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceJourneeService::class => FormationInstanceJourneeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstanceJourneeController::class => FormationInstanceJourneeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationJourneeForm::class => FormationJourneeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationJourneeHydrator::class => FormationJourneeHydratorFactory::class,
        ],
    ]

];