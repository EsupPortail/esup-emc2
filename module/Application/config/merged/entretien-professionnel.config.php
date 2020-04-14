<?php

namespace Application;

use Application\Controller\EntretienProfessionnelController;
use Application\Controller\EntretienProfessionnelControllerFactory;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelFormFactory;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelHydrator;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelHydratorFactory;
use Application\Provider\Privilege\EntretienproPrivileges;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_INDEX,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'creer',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'afficher',
                        'valider-element',
                        'revoquer-validation',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'entretienpro' => [
                                'label' => 'Entretiens professionnels',
                                'route' => 'entretien-professionnel',
                                'resource' =>  EntretienproPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_INDEX) ,
                                'order'    => 500,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'entretien-professionnel' => [
                'type'  => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/entretien-professionnel',
                    'defaults' => [
                        'controller' => EntretienProfessionnelController::class,
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'creer' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/afficher/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/historiser/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/restaurer/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/detruire/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'valider-element' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/valider-element/:entretien/:type',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'valider-element',
                            ],
                        ],
                    ],
                    'revoquer-validation' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/revoquer-validation/:validation',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'revoquer-validation',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            EntretienProfessionnelService::class => EntretienProfessionnelServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            EntretienProfessionnelController::class => EntretienProfessionnelControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            EntretienProfessionnelForm::class => EntretienProfessionnelFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EntretienProfessionnelHydrator::class => EntretienProfessionnelHydratorFactory::class,
        ],
    ]

];