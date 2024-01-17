<?php

namespace Formation;

use Formation\Controller\DomaineController;
use Formation\Controller\DomaineControllerFactory;
use Formation\Form\Domaine\DomaineForm;
use Formation\Form\Domaine\DomaineFormFactory;
use Formation\Form\Domaine\DomaineHydrator;
use Formation\Form\Domaine\DomaineHydratorFactory;
use Formation\Provider\Privilege\FormationdomainePrivileges;
use Formation\Service\Domaine\DomaineService;
use Formation\Service\Domaine\DomaineServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationdomainePrivileges::FORMATIONDOMAINE_INDEX,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormationdomainePrivileges::FORMATIONDOMAINE_AFFICHER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationdomainePrivileges::FORMATIONDOMAINE_AJOUTER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'modifier',
                        'gerer-formations',
                    ],
                    'privileges' => [
                        FormationdomainePrivileges::FORMATIONDOMAINE_MODIFIER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FormationdomainePrivileges::FORMATIONDOMAINE_HISTORISER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationdomainePrivileges::FORMATIONDOMAINE_SUPPRIMER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'domaine' => [
                                'label'    => 'Domaine de formation',
                                'route'    => 'formation-domaine',
                                'resource' => PrivilegeController::getResourceId(DomaineController::class, 'index') ,
                                'order'    => 206,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formation-domaine' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-domaine',
                    'defaults' => [
                        /** @see DomaineController::indexAction() */
                        'controller' => DomaineController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:domaine',
                            'defaults' => [
                                /** @see DomaineController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see DomaineController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:domaine',
                            'defaults' => [
                                /** @see DomaineController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'gerer-formations' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-formations/:domaine',
                            'defaults' => [
                                /** @see DomaineController::gererFormationsAction() */
                                'action'     => 'gerer-formations',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:domaine',
                            'defaults' => [
                                /** @see DomaineController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:domaine',
                            'defaults' => [
                                /** @see DomaineController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:domaine',
                            'defaults' => [
                                /** @see DomaineController::supprimerAction() */
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            DomaineService::class => DomaineServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            DomaineController::class => DomaineControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            DomaineForm::class => DomaineFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            DomaineHydrator::class => DomaineHydratorFactory::class,
        ],
    ]

];