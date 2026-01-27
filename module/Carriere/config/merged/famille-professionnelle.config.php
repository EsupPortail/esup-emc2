<?php

namespace Carriere;

use Carriere\Controller\FamilleProfessionnelleController;
use Carriere\Controller\FamilleProfessionnelleControllerFactory;
use Carriere\Form\FamilleProfessionnelle\FamilleProfessionnelleForm;
use Carriere\Form\FamilleProfessionnelle\FamilleProfessionnelleFormFactory;
use Carriere\Form\FamilleProfessionnelle\FamilleProfessionnelleHydrator;
use Carriere\Form\FamilleProfessionnelle\FamilleProfessionnelleHydratorFactory;
use Carriere\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleForm;
use Carriere\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleFormFactory;
use Carriere\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleHydrator;
use Carriere\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleHydratorFactory;
use Carriere\Provider\Privilege\FamilleprofessionnellePrivileges;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_INDEX
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_AFFICHER
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_AJOUTER
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_MODIFIER
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_HISTORISER
                    ],
                ],
                [
                    'controller' => FamilleProfessionnelleController::class,
                    'action' => [
                        'effacer',
                        'supprimer',
                    ],
                    'privileges' => [
                        FamilleprofessionnellePrivileges::FAMILLE_PROFESSIONNELLE_SUPPRIMER
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'famille-professionnelle' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/famille-professionnelle',
                    'defaults' => [
                        'controller' => FamilleProfessionnelleController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see FamilleProfessionnelleController::ajouterAction() */
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:famille-professionnelle',
                            'defaults' => [
                                /** @see FamilleProfessionnelleController::afficherAction() */
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:famille-professionnelle',
                            'defaults' => [
                                /** @see FamilleProfessionnelleController::modifierAction() */
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-famille/:famille-professionnelle',
                            'defaults' => [
                                /** @see FamilleProfessionnelleController::historiserAction() */
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-famille/:famille-professionnelle',
                            'defaults' => [
                                /** @see FamilleProfessionnelleController::restaurerAction() */
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer-famille/:famille-professionnelle',
                            'defaults' => [
                                /** @see FamilleProfessionnelleController::supprimerAction() */
                                'controller' => FamilleProfessionnelleController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'ressource' => [
                        'pages' => [
                            [
                                'order' => 2010,
                                'label' => 'Familles professionnelles',
                                'route' => 'famille-professionnelle',
                                'resource' => PrivilegeController::getResourceId(FamilleProfessionnelleController::class, 'index'),
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
            FamilleProfessionnelleService::class => FamilleProfessionnelleServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FamilleProfessionnelleController::class => FamilleProfessionnelleControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SelectionnerFamilleProfessionnelleForm::class  => SelectionnerFamilleProfessionnelleFormFactory::class,
            FamilleProfessionnelleForm::class => FamilleProfessionnelleFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SelectionnerFamilleProfessionnelleHydrator::class  => SelectionnerFamilleProfessionnelleHydratorFactory::class,
            FamilleProfessionnelleHydrator::class => FamilleProfessionnelleHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
];
