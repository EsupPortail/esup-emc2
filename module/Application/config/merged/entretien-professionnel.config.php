<?php

namespace Application;

use Application\Controller\EntretienProfessionnelController;
use Application\Controller\EntretienProfessionnelControllerFactory;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelFormFactory;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelHydrator;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelHydratorFactory;
use Application\Form\EntretienProfessionnelCampagne\EntretienProfessionnelCampagneForm;
use Application\Form\EntretienProfessionnelCampagne\EntretienProfessionnelCampagneFormFactory;
use Application\Form\EntretienProfessionnelCampagne\EntretienProfessionnelCampagneHydrator;
use Application\Form\EntretienProfessionnelCampagne\EntretienProfessionnelCampagneHydratorFactory;
use Application\Provider\Privilege\EntretienproPrivileges;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneServiceFactory;
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
                        'afficher',
                        'exporter',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'creer',
                        'modifier',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AJOUTER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'renseigner',
                        'valider-element',
                        'revoquer-validation',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_HISTORISER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_DETRUIRE,
                    ],
                ],

                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'ajouter-campagne',
                        'modifier-campagne',
                        'historiser-campagne',
                        'restaurer-campagne',
                        'detruire-campagne',
                    ],
                    'roles' => [],
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
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer[/:campagne]',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier/:entretien[/:structure]',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'modifier',
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
                    'exporter' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/exporter/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'exporter',
                            ],
                        ],
                    ],
                    'renseigner' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/renseigner/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'renseigner',
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
                    'campagne' => [
                        'type'  => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/campagne',
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'ajouter-campagne',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:campagne',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'modifier-campagne',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:campagne',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'historiser-campagne',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:campagne',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'restaurer-campagne',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire/:campagne',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'detruire-campagne',
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            EntretienProfessionnelService::class => EntretienProfessionnelServiceFactory::class,
            EntretienProfessionnelCampagneService::class => EntretienProfessionnelCampagneServiceFactory::class,
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
            EntretienProfessionnelCampagneForm::class => EntretienProfessionnelCampagneFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EntretienProfessionnelHydrator::class => EntretienProfessionnelHydratorFactory::class,
            EntretienProfessionnelCampagneHydrator::class => EntretienProfessionnelCampagneHydratorFactory::class,
        ],
    ]

];