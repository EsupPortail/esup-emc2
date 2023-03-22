<?php

namespace Metier;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Metier\Controller\DomaineController;
use Metier\Controller\FamilleProfessionnelleController;
use Metier\Controller\MetierController;
use Metier\Controller\MetierControllerFactory;
use Metier\Controller\ReferentielController;
use Metier\Form\Metier\MetierForm;
use Metier\Form\Metier\MetierFormFactory;
use Metier\Form\Metier\MetierHydrator;
use Metier\Form\Metier\MetierHydratorFactory;
use Metier\Form\SelectionnerMetier\SelectionnerMetierForm;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormFactory;
use Metier\Form\SelectionnerMetier\SelectionnerMetierHydrator;
use Metier\Form\SelectionnerMetier\SelectionnerMetierHydratorFactory;
use Metier\Provider\Privilege\MetierPrivileges;
use Metier\Service\Metier\MetierService;
use Metier\Service\Metier\MetierServiceFactory;
use Metier\View\Helper\TypeFonctionViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'index'
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_INDEX,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'modifier',
                        'modifier-niveaux',
                        'initialiser-niveaux',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'cartographie',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_CARTOGRAPHIE,
                    ],
                ],
                [
                    'controller' => MetierController::class,
                    'action' => [
                        'lister-agents',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AFFICHER,
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
                                'order' => 600,
                                'label' => 'Ressources liées aux métiers',
                                'route' => 'metier',
                                'resource' => PrivilegeController::getResourceId(MetierController::class, 'index'),
                                'dropdown-header' => true,
                            ],
                            [
                                'order' => 610,
                                'label' => 'Métiers',
                                'route' => 'metier',
                                'resource' => PrivilegeController::getResourceId(MetierController::class, 'index'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 620,
                                'label' => 'Domaines',
                                'route' => 'domaine',
                                'resource' => PrivilegeController::getResourceId(DomaineController::class, 'index'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 630,
                                'label' => 'Familles professionnelles',
                                'route' => 'famille-professionnelle',
                                'resource' => PrivilegeController::getResourceId(FamilleProfessionnelleController::class, 'index'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 640,
                                'label' => 'Référentiels métiers',
                                'route' => 'metier/referentiel',
                                'resource' => PrivilegeController::getResourceId(ReferentielController::class, 'index'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 650,
                                'label' => 'Cartographie',
                                'route' => 'metier/cartographie',
                                'resource' => PrivilegeController::getResourceId(MetierController::class, 'cartographie'),
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
            'metier' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/metier',
                    'defaults' => [
                        /** @see MetierController::indexAction() */
                        'controller' => MetierController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [

                    /** METIER ****************************************************************************************/

                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                /** @see MetierController::ajouterAction() */
                                'controller' => MetierController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:metier',
                            'defaults' => [
                                /** @see MetierController::modifierAction() */
                                'controller' => MetierController::class,
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:metier',
                            'defaults' => [
                                /** @see MetierController::historiserAction() */
                                'controller' => MetierController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:metier',
                            'defaults' => [
                                /** @see MetierController::restaurerAction() */
                                'controller' => MetierController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:metier',
                            'defaults' => [
                                /** @see MetierController::supprimerAction() */
                                'controller' => MetierController::class,
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'initialiser-niveaux' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/initialiser-niveaux',
                            'defaults' => [
                                /** @see MetierController::initialiserNiveauxAction() */
                                'controller' => MetierController::class,
                                'action' => 'initialiser-niveaux',
                            ],
                        ],
                    ],
                    'modifier-niveaux' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-niveaux/:metier',
                            'defaults' => [
                                /** @see MetierController::modifierNiveauxAction() */
                                'controller' => MetierController::class,
                                'action' => 'modifier-niveaux',
                            ],
                        ],
                    ],
                    'lister-agents' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/lister-agents/:metier',
                            'defaults' => [
                                /** @see MetierController::listerAgentsAction() */
                                'controller' => MetierController::class,
                                'action' => 'lister-agents',
                            ],
                        ],
                    ],

                    /** CARTOGRAPHIE **********************************************************************************/

                    'cartographie' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/cartographie',
                            'defaults' => [
                                /** @see MetierController::cartographieAction() */
                                'controller' => MetierController::class,
                                'action' => 'cartographie',
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
            MetierService::class => MetierServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            MetierController::class => MetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            MetierForm::class => MetierFormFactory::class,
            SelectionnerMetierForm::class => SelectionnerMetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            MetierHydrator::class => MetierHydratorFactory::class,
            SelectionnerMetierHydrator::class => SelectionnerMetierHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'typefonction' => TypeFonctionViewHelper::class,
        ],
    ],
];
