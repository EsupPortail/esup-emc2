<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Assertion\EntretienProfessionnelAssertion;
use EntretienProfessionnel\Assertion\EntretienProfessionnelAssertionFactory;
use EntretienProfessionnel\Controller\EntretienProfessionnelController;
use EntretienProfessionnel\Controller\EntretienProfessionnelControllerFactory;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceFactory;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormFactory;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelHydrator;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelHydratorFactory;
use EntretienProfessionnel\View\Helper\EntretienProfessionnelArrayViewHelper;
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
                            EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                            EntretienproPrivileges::ENTRETIENPRO_HISTORISER,
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
                        'accepter-entretien',
                        'rechercher-agent',
                        'rechercher-responsable',
                        'rechercher-structure',
                    ],
                    'roles' => [],
                ],
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
                        'find-responsable-pour-entretien',
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
                    'find-responsable-pour-entretien' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/find-responsable-pour-entretien[/:structure/:campagne]',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'find-responsable-pour-entretien',
                            ],
                        ],
                    ],
                    'accepter-entretien' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/accepter-entretien/:entretien-professionnel/:token',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'accepter-entretien',
                            ],
                        ],
                    ],
                    'rechercher-responsable' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/rechercher-responsable',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'rechercher-responsable',
                            ],
                        ],
                    ],
                    'rechercher-agent' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/rechercher-agent',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'rechercher-agent',
                            ],
                        ],
                    ],
                    'rechercher-structure' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/rechercher-structure',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'rechercher-structure',
                            ],
                        ],
                    ],
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

    'service_manager' => [
        'factories' => [
            EntretienProfessionnelService::class => EntretienProfessionnelServiceFactory::class,
            EntretienProfessionnelAssertion::class => EntretienProfessionnelAssertionFactory::class,
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
    ],
    'view_helpers' => [
        'invokables' => [
            'entretienProfessionnelArray' => EntretienProfessionnelArrayViewHelper::class,
        ],
    ],


];