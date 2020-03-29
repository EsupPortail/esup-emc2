<?php

namespace Application;

use Application\Controller\AgentController;
use Application\Controller\AgentControllerFactory;
use Application\Controller\AgentFichierController;
use Application\Controller\AgentFichierControllerFactory;
use Application\Controller\EntretienProfessionnelController;
use Application\Form\Agent\AgentForm;
use Application\Form\Agent\AgentFormFactory;
use Application\Form\Agent\AgentHydrator;
use Application\Form\Agent\AgentHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\Agent\AgentService;
use Application\Service\Agent\AgentServiceFactory;
use Application\View\Helper\AgentGradeViewHelper;
use Application\View\Helper\AgentStatutViewHelper;
use Application\View\Helper\AgentViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'rechercher',
                        'rechercher-responsable',
                        'ajouter-agent-competence',
                        'afficher-agent-competence',
                        'modifier-agent-competence',
                        'historiser-agent-competence',
                        'restaurer-agent-competence',
                        'detruire-agent-competence',
                    ],
                    'privileges' => [
                        AgentPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'rechercher-individu',
                    ],
                    'privileges' => [
                        AgentPrivileges::AJOUTER,
                    ],
                ],
                [
                    'controller' => AgentController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        AgentPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => AgentFichierController::class,
                    'action' => [
                        'index',
                        'upload'
                    ],
                    'privileges' => [
                        AgentPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => EntretienProfessionnelController::class,
                    'action' => [
                        'index-agent',
                    ],
                    'privileges' => [
                        AgentPrivileges::AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'agent' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/agent',
                    'defaults' => [
                        'controller' => AgentController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'rechercher' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher',
                            ],
                        ],
                    ],
                    'rechercher-responsable' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-responsable',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-responsable',
                            ],
                        ],
                    ],
                    'ajouter-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent-competence/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'ajouter-agent-competence',
                            ],
                        ],
                    ],
                    'afficher-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher-agent-competence',
                            ],
                        ],
                    ],
                    'modifier-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier-agent-competence',
                            ],
                        ],
                    ],
                    'historiser-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'historiser-agent-competence',
                            ],
                        ],
                    ],
                    'restaurer-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'restaurer-agent-competence',
                            ],
                        ],
                    ],
                    'detruire-agent-competence' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire-agent-competence/:agent-competence',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'detruire-agent-competence',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:agent',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'fichiers' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/fichiers[/:agent]',
                            'defaults' => [
                                'controller' => AgentFichierController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'entretien-professionnel' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/entretien-professionnel[/:agent]',
                            'defaults' => [
                                'controller' => EntretienProfessionnelController::class,
                                'action'     => 'index-agent',
                            ],
                        ],
                    ],
                    'upload-fichier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/upload-fichier/:agent/:nature',
                            'defaults' => [
                                'controller' => AgentFichierController::class,
                                'action'     => 'upload',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'rechercher-individu' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher-individu',
                            'defaults' => [
                                'controller' => AgentController::class,
                                'action'     => 'rechercher-individu',
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
                    'ressource' => [
                        'pages' => [
                            'agent' => [
                                'label'    => 'Agents',
                                'route'    => 'agent',
                                'resource' => AgentPrivileges::getResourceId(AgentPrivileges::AFFICHER),
                                'order'    => 100,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            AgentService::class => AgentServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentController::class => AgentControllerFactory::class,
            AgentFichierController::class => AgentFichierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentForm::class => AgentFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentHydrator::class => AgentHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agent' => AgentViewHelper::class,
            'agentStatut' => AgentStatutViewHelper::class,
            'agentGrade' => AgentGradeViewHelper::class,
        ],
    ],


];