<?php

namespace Application;

use Application\Controller\ParcoursDeFormationController;
use Application\Controller\ParcoursDeFormationControllerFactory;
use Application\Form\AjouterFormation\AjouterFormationForm;
use Application\Form\AjouterFormation\AjouterFormationFormFactory;
use Application\Form\AjouterFormation\AjouterFormationHydrator;
use Application\Form\AjouterFormation\AjouterFormationHydratorFactory;
use Application\Form\ModifierRattachement\ModifierRattachementForm;
use Application\Form\ModifierRattachement\ModifierRattachementFormFactory;
use Application\Form\ModifierRattachement\ModifierRattachementHydrator;
use Application\Form\ModifierRattachement\ModifierRattachementHydratorFactory;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationForm;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationFormFactory;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationHydrator;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationHydratorFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceFactory;
use Application\View\Helper\ParcoursDeFormationViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ParcoursDeFormationController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',

                        'modifier-libelle',
                        'modifier-rattachement',
                        'ajouter-formation',
                        'retirer-formation',
                        'bouger-formation',

                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_ACCES,
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
                            'parcours-de-formation' => [
                                'label'    => 'Parcours de formation',
                                'route'    => 'parcours-de-formation',
                                'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_AFFICHER),
                                'order'    => 1150,
                                'pages' => [
                                    'modifier-parcours-de-foamtion' => [
                                        'label'    => 'Parcours de formation',
                                        'route'    => 'parcours-de-formation/modifier',
                                        'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_AFFICHER),
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'parcours-de-formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/parcours-de-formation',
                    'defaults' => [
                        'controller' => ParcoursDeFormationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter[/:type]',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-libelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-libelle/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'modifier-libelle',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-rattachement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-rattachement/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'modifier-rattachement',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-formation/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'ajouter-formation',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'retirer-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-formation/:parcours-de-formation/:formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'retirer-formation',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'bouger-formation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/bouger-formation/:parcours-de-formation/:formation/:direction',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'bouger-formation',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:parcours-de-formation',
                            'defaults' => [
                                'controller' => ParcoursDeFormationController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ParcoursDeFormationService::class => ParcoursDeFormationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ParcoursDeFormationController::class => ParcoursDeFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AjouterFormationForm::class => AjouterFormationFormFactory::class,
            ModifierRattachementForm::class => ModifierRattachementFormFactory::class,
            ParcoursDeFormationForm::class => ParcoursDeFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AjouterFormationHydrator::class => AjouterFormationHydratorFactory::class,
            ModifierRattachementHydrator::class => ModifierRattachementHydratorFactory::class,
            ParcoursDeFormationHydrator::class => ParcoursDeFormationHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'parcoursDeFormation'  => ParcoursDeFormationViewHelperFactory::class,
        ],
    ],

];