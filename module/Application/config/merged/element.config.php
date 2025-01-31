<?php

namespace Application;

use Application\Controller\ElementController;
use Application\Controller\ElementControllerFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Element\Provider\Privilege\CompetencePrivileges;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use Application\View\Helper\FormationBlocViewHelper;
use Element\Provider\Privilege\ApplicationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ElementController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_AFFICHER,
                        AgentPrivileges::AGENT_ACQUIS_AFFICHER,
                    ],
                ],
                [
                    'controller' => ElementController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        ApplicationPrivileges::APPLICATION_EFFACER,
                        CompetencePrivileges::COMPETENCE_EFFACER,
                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                    ],
                ],
                [
                    'controller' => ElementController::class,
                    'action' => [
                        'changer-niveau',
                        'ajouter-application-element',
                        'modifier-application-element',
                        'ajouter-competence-element',
                        'modifier-competence-element',
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_ELEMENT_MODIFIER,
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'element_' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/element',
                    'defaults' => [
                        'controller' => ElementController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:type/:id',
                            'defaults' => [
                                'controller' => ElementController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:type/:id',
                            'defaults' => [
                                'controller' => ElementController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'changer-niveau' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/changer-niveau/:type/:id[/:clef]',
                            'defaults' => [
                                'controller' => ElementController::class,
                                'action'     => 'changer-niveau',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-application-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-application-element/:type/:id[/:clef]',
                            'defaults' => [
                                'controller' => ElementController::class,
                                'action'     => 'ajouter-application-element',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-application-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-application-element/:application-element',
                            'defaults' => [
                                /** @see ElementController::modifierApplicationElementAction() */
                                'action'     => 'modifier-application-element',
                            ],
                        ],
                    ],
                    'ajouter-competence-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-competence-element/:type/:id[/:clef]',
                            'defaults' => [
                                'controller' => ElementController::class,
                                'action'     => 'ajouter-competence-element',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-competence-element' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-competence-element/:competence-element',
                            'defaults' => [
                                /** @see ElementController::modifierCompetenceElementAction() */
                                'action'     => 'modifier-competence-element',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [],
    ],
    'controllers'     => [
        'factories' => [
            ElementController::class => ElementControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [],
    ],
    'view_helpers' => [
        'invokables' => [
            'formationBloc' => FormationBlocViewHelper::class,
        ],
    ]

];