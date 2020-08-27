<?php

namespace Application;

use Application\Controller\FonctionController;
use Application\Controller\FonctionControllerFactory;
use Application\Form\FonctionActivite\FonctionActiviteForm;
use Application\Form\FonctionActivite\FonctionActiviteFormFactory;
use Application\Form\FonctionActivite\FonctionActiviteHydrator;
use Application\Form\FonctionActivite\FonctionActiviteHydratorFactory;
use Application\Form\FonctionDestination\FonctionDestinationForm;
use Application\Form\FonctionDestination\FonctionDestinationFormFactory;
use Application\Form\FonctionDestination\FonctionDestinationHydrator;
use Application\Form\FonctionDestination\FonctionDestinationHydratorFactory;
use Application\Provider\Privilege\FormationPrivileges;
use Application\Service\Fonction\FonctionService;
use Application\Service\Fonction\FonctionServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FonctionController::class,
                    'action' => [
                        'index',

                        'ajouter-destination',
                        'modifier-destination',
                        'supprimer-destination',
                        'ajouter-activite',
                        'modifier-activite',
                        'supprimer-activite',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_INDEX,
                    ],
                ],
            ],
        ],
    ],


    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            'fonction' => [
                                'label'    => 'Fonctions',
                                'route'    => 'fonction',
                                'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_AFFICHER),
                                'order'    => 680,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fonction' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fonction',
                    'defaults' => [
                        'controller' => FonctionController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'destination' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/destination',
                            'defaults' => [
                                'controller' => FonctionController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => FonctionController::class,
                                        'action' => 'ajouter-destination',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:destination',
                                    'defaults' => [
                                        'controller' => FonctionController::class,
                                        'action' => 'modifier-destination',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:destination',
                                    'defaults' => [
                                        'controller' => FonctionController::class,
                                        'action' => 'supprimer-destination',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'activite' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/activite',
                            'defaults' => [
                                'controller' => FonctionController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => FonctionController::class,
                                        'action' => 'ajouter-activite',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:activite',
                                    'defaults' => [
                                        'controller' => FonctionController::class,
                                        'action' => 'modifier-activite',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:activite',
                                    'defaults' => [
                                        'controller' => FonctionController::class,
                                        'action' => 'supprimer-activite',
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
            FonctionService::class => FonctionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FonctionController::class => FonctionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FonctionActiviteForm::class => FonctionActiviteFormFactory::class,
            FonctionDestinationForm::class => FonctionDestinationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FonctionActiviteHydrator::class => FonctionActiviteHydratorFactory::class,
            FonctionDestinationHydrator::class => FonctionDestinationHydratorFactory::class,
        ],
    ],

];