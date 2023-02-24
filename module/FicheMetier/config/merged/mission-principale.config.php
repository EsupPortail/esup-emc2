<?php

namespace FichePoste;

use FicheMetier\Controller\MissionPrincipaleController;
use FicheMetier\Controller\MissionPrincipaleControllerFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Provider\Privilege\MissionPrincipalePrivileges;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MissionPrincipaleController::class,
                    'action' => [
                        'index',
                        'afficher',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AFFICHER,
                    ],
                ],
                [
                    'controller' => MissionPrincipaleController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MissionPrincipalePrivileges::MISSIONPRINCIPALE_AJOUTER,
                    ],
                ],
                [
                    'controller' => MissionPrincipaleController::class,
                    'action' => [
                        'modifier',

                        'modifier-libelle',
                        'gerer-domaines',

                        'ajouter-activite',
                        'modifier-activite',
                        'supprimer-activite',

                    ],
                    'privileges' => [
                        MissionPrincipalePrivileges::MISSIONPRINCIPALE_MODIFIER,
                    ],
                ],
                [
                    'controller' => MissionPrincipaleController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MissionPrincipalePrivileges::MISSIONPRINCIPALE_HISTORISER,
                    ],
                ],
                [
                    'controller' => MissionPrincipaleController::class,
                    'action' => [
                        'supprimer'
                    ],
                    'privileges' => [
                        MissionPrincipalePrivileges::MISSIONPRINCIPALE_SUPPRIMER,
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
                            'mission-pincipale' => [
                                'label'    => 'Missions principales',
                                'route'    => 'mission-principale',
                                'resource' => PrivilegeController::getResourceId(MissionPrincipaleController::class, 'index') ,
                                'order'    => 1020,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'mission-principale' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/mission-principale',
                    'defaults' => [
                        /** @see MissionPrincipaleController::indexAction() */
                        'controller' => MissionPrincipaleController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::afficherAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see MissionPrincipaleController::ajouterAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::modifierAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::historiserAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::restaurerAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::supprimerAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],

                    'modifier-libelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-libelle/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::modifierLibelleAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'modifier-libelle',
                            ],
                        ],
                    ],
                    'ajouter-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-activite/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::ajouterActiviteAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'ajouter-activite',
                            ],
                        ],
                    ],
                    'modifier-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-activite/:activite',
                            'defaults' => [
                                /** @see MissionPrincipaleController::modifierActiviteAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'modifier-activite',
                            ],
                        ],
                    ],
                    'supprimer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-activite/:activite',
                            'defaults' => [
                                /** @see MissionPrincipaleController::supprimerActiviteAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'supprimer-activite',
                            ],
                        ],
                    ],
                    'gerer-domaines' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-domaines/:mission-principale',
                            'defaults' => [
                                /** @see MissionPrincipaleController::gererDomainesAction() */
                                'controller' => MissionPrincipaleController::class,
                                'action'     => 'gerer-domaines',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            MissionPrincipaleService::class => MissionPrincipaleServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            MissionPrincipaleController::class => MissionPrincipaleControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];