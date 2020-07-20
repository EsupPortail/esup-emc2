<?php

namespace Application;

use Application\Assertion\EntretienProfessionnelAssertion;
use Application\Assertion\EntretienProfessionnelAssertionFactory;
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
use Application\Form\EntretienProfessionnelObservation\EntretienProfessionnelObservationForm;
use Application\Form\EntretienProfessionnelObservation\EntretienProfessionnelObservationFormFactory;
use Application\Form\EntretienProfessionnelObservation\EntretienProfessionnelObservationHydrator;
use Application\Form\EntretienProfessionnelObservation\EntretienProfessionnelObservationHydratorFactory;
use Application\Provider\Privilege\CampagnePrivileges;
use Application\Provider\Privilege\EntretienproPrivileges;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneServiceFactory;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelObservationService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelObservationServiceFactory;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'EntretienProfessionnel' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_AGENT,
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_RESPONSABLE,
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_DRH,
                        ],
                        'resources' => ['EntretienProfessionnel'],
                        'assertion' => EntretienProfessionnelAssertion::class
                    ],
                ],
            ],
        ],
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
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_AJOUTER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'modifier-campagne',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_MODIFIER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'historiser-campagne',
                        'restaurer-campagne',
                        'detruire-campagne',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_HISTORISER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'detruire-campagne',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_DETRUIRE,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'ajouter-observation',
                        'modifier-observation',
                        'historiser-observation',
                        'restaurer-observation',
                        'detruire-observation',
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
                    /** CAMPAGNE */
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
                    /** OBSERVATION */
                    'observation' => [
                        'type'  => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/observation',
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter/:entretien-professionnel',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'ajouter-observation',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:observation',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'modifier-observation',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:observation',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'historiser-observation',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:observation',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'restaurer-observation',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire/:observation',
                                    'defaults' => [
                                        'controller' => EntretienProfessionnelController::class,
                                        'action'     => 'detruire-observation',
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
            EntretienProfessionnelAssertion::class => EntretienProfessionnelAssertionFactory::class,
            EntretienProfessionnelService::class => EntretienProfessionnelServiceFactory::class,
            EntretienProfessionnelCampagneService::class => EntretienProfessionnelCampagneServiceFactory::class,
            EntretienProfessionnelObservationService::class => EntretienProfessionnelObservationServiceFactory::class,
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
            EntretienProfessionnelObservationForm::class => EntretienProfessionnelObservationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EntretienProfessionnelHydrator::class => EntretienProfessionnelHydratorFactory::class,
            EntretienProfessionnelCampagneHydrator::class => EntretienProfessionnelCampagneHydratorFactory::class,
            EntretienProfessionnelObservationHydrator::class => EntretienProfessionnelObservationHydratorFactory::class,
        ],
    ]

];