<?php

namespace Formation;

use Formation\Controller\FormationInstanceFormateurController;
use Formation\Controller\FormationInstanceFormateurControllerFactory;
use Formation\Form\FormationInstanceFormateur\FormationInstanceFormateurForm;
use Formation\Form\FormationInstanceFormateur\FormationInstanceFormateurFormFactory;
use Formation\Form\FormationInstanceFormateur\FormationInstanceFormateurHydrator;
use Formation\Form\FormationInstanceFormateur\FormationInstanceFormateurHydratorFactory;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\FormationInstanceFormateur\FormationInstanceFormateurService;
use Formation\Service\FormationInstanceFormateur\FormationInstanceFormateurServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceFormateurController::class,
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
                                'controller' => FormationInstanceFormateurController::class,
                                'action'     => 'ajouter-formateur',
                            ],
                        ],
                    ],
                    'modifier-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formateur/:formateur',
                            'defaults' => [
                                'controller' => FormationInstanceFormateurController::class,
                                'action'     => 'modifier-formateur',
                            ],
                        ],
                    ],
                    'historiser-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-journee/:formateur',
                            'defaults' => [
                                'controller' => FormationInstanceFormateurController::class,
                                'action'     => 'historiser-formateur',
                            ],
                        ],
                    ],
                    'restaurer-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-formateur/:formateur',
                            'defaults' => [
                                'controller' => FormationInstanceFormateurController::class,
                                'action'     => 'restaurer-formateur',
                            ],
                        ],
                    ],
                    'supprimer-formateur' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-formateur/:formateur',
                            'defaults' => [
                                'controller' => FormationInstanceFormateurController::class,
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
            FormationInstanceFormateurService::class => FormationInstanceFormateurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstanceFormateurController::class => FormationInstanceFormateurControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationInstanceFormateurForm::class => FormationInstanceFormateurFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationInstanceFormateurHydrator::class => FormationInstanceFormateurHydratorFactory::class,
        ],
    ]

];