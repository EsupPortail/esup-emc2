<?php

namespace Formation;

use Application\Controller\IndexController;
use Formation\Controller\FormationInstanceInscritController;
use Formation\Controller\FormationInstanceInscritControllerFactory;
use Formation\Provider\Privilege\FormationinstanceinscritPrivileges;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'ajouter-agent',
                        'historiser-agent',
                        'restaurer-agent',
                        'supprimer-agent',
                        'envoyer-liste-principale',
                        'envoyer-liste-complementaire',
                    ],
                    'privileges' => [
                        FormationinstanceinscritPrivileges::FORMATIONINSTANCEINSCRIT_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'liste-formations-instances',
                        'inscription',
                        'desinscription',
                    ],
                    'roles' => [
                        'Agent',
                    ],
                ],
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'valider-responsable',
                        'valider-drh',
                    ],
                    'privileges' => [
                        FormationinstanceinscritPrivileges::FORMATIONINSTANCEINSCRIT_MODIFIER,
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'formations' => [
                        'order' => 1000,
                        'label' => 'Formations',
                        'title' => "Gestion des actions de formations et des inscriptions à celles-ci",
                        'route' => 'liste-formations-instances',
                        'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'liste-formations-instances'),
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'liste-formations-instances' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/liste-formations-instances',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'liste-formations-instances',
                    ],
                ],
            ],
            'formation-instance' => [
                'child_routes' => [
                    'inscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/inscription/:formation-instance/:agent',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'inscription',
                            ],
                        ],
                    ],
                    'desinscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/desinscription/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'desinscription',
                            ],
                        ],
                    ],
                    'valider-responsable' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/valider-responsable/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'valider-responsable',
                            ],
                        ],
                    ],
                    'valider-drh' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/valider-drh/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'valider-drh',
                            ],
                        ],
                    ],
                    'ajouter-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'ajouter-agent',
                            ],
                        ],
                    ],
                    'historiser-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'historiser-agent',
                            ],
                        ],
                    ],
                    'restaurer-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'restaurer-agent',
                            ],
                        ],
                    ],
                    'supprimer-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'supprimer-agent',
                            ],
                        ],
                    ],
                    'envoyer-liste-principale' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-liste-principale/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'envoyer-liste-principale',
                            ],
                        ],
                    ],
                    'envoyer-liste-complementaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-liste-complementaire/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'envoyer-liste-complementaire',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceInscritService::class => FormationInstanceInscritServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstanceInscritController::class => FormationInstanceInscritControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];