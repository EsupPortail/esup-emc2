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
use EntretienProfessionnel\View\Helper\ConvocationArrayViewHelper;
use EntretienProfessionnel\View\Helper\EntretienProfessionnelArrayViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

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
                            EntretienproPrivileges::ENTRETIENPRO_EXPORTER,
                            EntretienproPrivileges::ENTRETIENPRO_CONVOQUER,
                            EntretienproPrivileges::ENTRETIENPRO_MODIFIER,
                            EntretienproPrivileges::ENTRETIENPRO_HISTORISER,
                            EntretienproPrivileges::ENTRETIENPRO_RENSEIGNER,
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_AGENT,
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_RESPONSABLE,
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_DRH,
                            EntretienproPrivileges::ENTRETIENPRO_VALIDER_OBSERVATION,
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
                        'action',
                        'retour-liste'
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'accepter-entretien',
                        'rechercher-agent',
                        'rechercher-responsable',
                        'rechercher-structure',
                    ],
                    'roles' => [
                        'user'
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'rechercher-entretien',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    ],
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
                        'index-agent',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'index-delegue',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'exporter-crep',
                        'exporter-cref',
                    ],
                    'privileges' => EntretienproPrivileges::ENTRETIENPRO_EXPORTER,
                    'assertion' => EntretienProfessionnelAssertion::class,
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'creer',
                        'modifier',
                    ],
                    'privileges' => EntretienproPrivileges::ENTRETIENPRO_CONVOQUER,
                    'assertion' => EntretienProfessionnelAssertion::class,
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'acceder',
                    ],
                    'privileges' => EntretienproPrivileges::ENTRETIENPRO_AFFICHER,
                    'assertion' => EntretienProfessionnelAssertion::class,
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
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
                    'retour-liste' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/retour-liste/:entretien-professionnel',
                            'defaults' => [
                                /** @see EntretienProfessionnelController::retourListeAction() */
                                'action'     => 'retour-liste',
                            ],
                        ],
                    ],
                    'index-delegue' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-delegue',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'index-delegue',
                            ],
                        ],
                    ],
                    'index-agent' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/index-agent',
                            'defaults' => [
                                /** @see EntretienProfessionnelController::indexAgentAction() */
                                'action'     => 'index-agent',
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
                    'rechercher-entretien' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/rechercher-entretien',
                            'defaults' => [
                                /** @see EntretienProfessionnelController::rechercherEntretienAction() */
                                'action'     => 'rechercher-entretien',
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
                    'action' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/action/:entretien-professionnel',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'action',
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
                    'exporter-crep' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/exporter-crep/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'exporter-crep',
                            ],
                        ],
                    ],
                    'exporter-cref' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/exporter-cref/:entretien',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'exporter-cref',
                            ],
                        ],
                    ],
                    'acceder' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/acceder/:entretien-professionnel',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'acceder',
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
                                'action'     => 'acceder',
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
                            'route'    => '/revoquer-validation/:entretien-professionnel/:validation',
                            'defaults' => [
                                /** @see EntretienProfessionnelController::revoquerValidationAction() */
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
                            'entretienpros' => [
                                'label' => 'Gestion des entretiens professionnels',
                                'route' => 'entretien-professionnel',
                                'resource' =>  EntretienproPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_INDEX) ,
                                'order'    => 3000,
                                'dropdown-header' => true,
                            ],'entretienpro' => [
                                'label' => 'Entretiens professionnels',
                                'route' => 'entretien-professionnel',
                                'resource' =>  EntretienproPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_INDEX) ,
                                'order'    => 3020,
                                'icon' => 'fas fa-angle-right',
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
            'convocationArray' => ConvocationArrayViewHelper::class,
            'entretienProfessionnelArray' => EntretienProfessionnelArrayViewHelper::class,
        ],
    ],


];