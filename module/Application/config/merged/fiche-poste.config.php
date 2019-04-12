<?php

namespace Application;

use Application\Controller\FichePoste\FichePosteController;
use Application\Controller\FichePoste\FichePosteControllerFactory;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierAgent\AssocierAgentFormFactory;
use Application\Form\AssocierAgent\AssocierAgentHydrator;
use Application\Form\AssocierAgent\AssocierAgentHydratorFactory;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FichePoste\FichePosteServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'index',
                        'ajouter',
                        'afficher',
                        'editer',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'associer-agent',
                    ],
                    'roles' => [
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-poste' => [
                'type'  => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/fiche-poste',
                    'defaults' => [
                        'controller' => FichePosteController::class,
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/afficher/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/editer/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/historiser/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/restaurer/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/detruire/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'associer-agent' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/associer-agent/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'associer-agent',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FichePosteService::class => FichePosteServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FichePosteController::class => FichePosteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AssocierAgentForm::class => AssocierAgentFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AssocierAgentHydrator::class => AssocierAgentHydratorFactory::class,
        ],
    ]

];