<?php

namespace Formation;

use Formation\Controller\FormateurController;
use Formation\Controller\FormateurControllerFactory;
use Formation\Form\Formateur\FormateurForm;
use Formation\Form\Formateur\FormateurFormFactory;
use Formation\Form\Formateur\FormateurHydrator;
use Formation\Form\Formateur\FormateurHydratorFactory;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\Formateur\FormateurServiceFactory;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormateurController::class,
                    'action' => [
                        'ajouter-formateur',
                        'modifier-formateur',
                        'historiser-formateur',
                        'restaurer-formateur',
                        'supprimer-formateur',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_FORMATEUR,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-instance' => [
                'child_routes' => [
                    'ajouter-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-formateur/:formation-instance',
                            'defaults' => [
                                'controller' => FormateurController::class,
                                'action'     => 'ajouter-formateur',
                            ],
                        ],
                    ],
                    'modifier-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formateur/:formateur',
                            'defaults' => [
                                'controller' => FormateurController::class,
                                'action'     => 'modifier-formateur',
                            ],
                        ],
                    ],
                    'historiser-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-journee/:formateur',
                            'defaults' => [
                                'controller' => FormateurController::class,
                                'action'     => 'historiser-formateur',
                            ],
                        ],
                    ],
                    'restaurer-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-formateur/:formateur',
                            'defaults' => [
                                'controller' => FormateurController::class,
                                'action'     => 'restaurer-formateur',
                            ],
                        ],
                    ],
                    'supprimer-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-formateur/:formateur',
                            'defaults' => [
                                'controller' => FormateurController::class,
                                'action'     => 'supprimer-formateur',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormateurService::class => FormateurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormateurController::class => FormateurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormateurForm::class => FormateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormateurHydrator::class => FormateurHydratorFactory::class,
        ],
    ]

];