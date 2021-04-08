<?php

namespace Application;

use Application\Controller\ElementController;
use Application\Controller\ElementControllerFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\CompetencePrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\View\Helper\FormationBlocViewHelper;
use Formation\Provider\Privilege\FormationPrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                        CompetencePrivileges::COMPETENCE_AFFICHER,
                        FormationPrivileges::FORMATION_AFFICHER,
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
                        FormationPrivileges::FORMATION_SUPPRIMER,
                        AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                    ],
                ],
                [
                    'controller' => ElementController::class,
                    'action' => [
                        'changer-niveau',
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
            'element' => [
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