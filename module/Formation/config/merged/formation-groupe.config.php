<?php

namespace Formation;

use Formation\Controller\FormationGroupeController;
use Formation\Controller\FormationGroupeControllerFactory;
use Formation\Form\FormationGroupe\FormationGroupeForm;
use Formation\Form\FormationGroupe\FormationGroupeFormFactory;
use Formation\Form\FormationGroupe\FormationGroupeHydrator;
use Formation\Form\FormationGroupe\FormationGroupeHydratorFactory;
use Formation\Form\SelectionFormationGroupe\SelectionFormationGroupeForm;
use Formation\Form\SelectionFormationGroupe\SelectionFormationGroupeFormFactory;
use Formation\Provider\Privilege\FormationgroupePrivileges;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationGroupe\FormationGroupeServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationGroupeController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationgroupePrivileges::FORMATIONGROUPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationGroupeController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormationgroupePrivileges::FORMATIONGROUPE_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationGroupeController::class,
                    'action' => [
                        'ajouter-groupe',
                    ],
                    'privileges' => [
                        FormationgroupePrivileges::FORMATIONGROUPE_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationGroupeController::class,
                    'action' => [
                        'editer-groupe',
                    ],
                    'privileges' => [
                        FormationgroupePrivileges::FORMATIONGROUPE_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationGroupeController::class,
                    'action' => [
                        'historiser-groupe',
                        'restaurer-groupe',
                    ],
                    'privileges' => [
                        FormationgroupePrivileges::FORMATIONGROUPE_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationGroupeController::class,
                    'action' => [
                        'detruire-groupe',
                        'dedoublonner'
                    ],
                    'privileges' => [
                        FormationgroupePrivileges::FORMATIONGROUPE_SUPPRIMER,
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
                            'groupe_' => [
                                'label'    => 'Groupes de formation',
                                'route'    => 'formation-groupe',
                                'resource' => PrivilegeController::getResourceId(FormationGroupeController::class, 'index') ,
                                'order'    => 320,
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
            'formation-groupe' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-groupe',
                    'defaults' => [
                        'controller' => FormationGroupeController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'ajouter-groupe',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'editer-groupe',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'historiser-groupe',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'restaurer-groupe',
                            ],
                        ],
                    ],
                    'dedoublonner' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dedoublonner/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'dedoublonner',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation-groupe',
                            'defaults' => [
                                'controller' => FormationGroupeController::class,
                                'action'     => 'detruire-groupe',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationGroupeService::class => FormationGroupeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationGroupeController::class => FormationGroupeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationGroupeForm::class => FormationGroupeFormFactory::class,
            SelectionFormationGroupeForm::class => SelectionFormationGroupeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationGroupeHydrator::class => FormationGroupeHydratorFactory::class,
        ],
    ],

];