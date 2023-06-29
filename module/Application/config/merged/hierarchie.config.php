<?php

namespace Application;

use Application\Controller\AgentHierarchieController;
use Application\Controller\AgentHierarchieControllerFactory;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculForm;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculFormFactory;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculHydrator;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculHydratorFactory;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationFormFactory;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationHydrator;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationHydratorFactory;
use Application\Form\AgentHierarchieSaisie\AgentHierarchieSaisieForm;
use Application\Form\AgentHierarchieSaisie\AgentHierarchieSaisieFormFactory;
use Application\Form\AgentHierarchieSaisie\AgentHierarchieSaisieHydrator;
use Application\Form\AgentHierarchieSaisie\AgentHierarchieSaisieHydratorFactory;
use Application\Provider\Privilege\AgentPrivileges;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentAutorite\AgentAutoriteServiceFactory;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\AgentSuperieur\AgentSuperieurServiceFactory;
use Application\View\Helper\AgentAutoriteViewHelper;
use Application\View\Helper\AgentSuperieurViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => AgentHierarchieController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'importer',
                        'calculer',
                        'saisir',
                        'chaine-hierarchique-json'
                    ],
                    'privileges' => [
                        AgentPrivileges::AGENT_INDEX, //todo "Faites mieux !!!"
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
                            'autre' => [
                                'label' => 'Autres gestions',
                                'route' => 'agent/hierarchie',
                                'resource' =>  PrivilegeController::getResourceId(AgentHierarchieController::class, 'index'),
                                'order'    => 9900,
                                'dropdown-header' => true,
                            ],
                            'hierarchie' => [
                                'label' => 'Gestion des chaînes hiérarchiques',
                                'route' => 'agent/hierarchie',
                                'resource' =>  PrivilegeController::getResourceId(AgentHierarchieController::class, 'index'),
                                'order'    => 9999,
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
            'agent' => [
                'child_routes' => [
                    'hierarchie' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/hierarchie',
                            'defaults' => [
                                /** @see AgentHierarchieController::indexAction() */
                                'controller' => AgentHierarchieController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'saisir' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/saisir',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::saisirAction(): */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'saisir',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:agent',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::afficherAction(): */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'chaine-hierarchique-json' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/chaine-hierarchique-json/:agent',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::chaineHierarchiqueJsonAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'chaine-hierarchique-json',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'importer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/importer',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::importerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'importer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'calculer' => [
                                'type'  => Literal::class,
                                'options' => [
                                    'route'    => '/calculer',
                                    'defaults' => [
                                        /** @see AgentHierarchieController::calculerAction() */
                                        'controller' => AgentHierarchieController::class,
                                        'action'     => 'calculer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentAutoriteService::class => AgentAutoriteServiceFactory::class,
            AgentSuperieurService::class => AgentSuperieurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AgentHierarchieController::class => AgentHierarchieControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentHierarchieCalculForm::class => AgentHierarchieCalculFormFactory::class,
            AgentHierarchieImportationForm::class => AgentHierarchieImportationFormFactory::class,
            AgentHierarchieSaisieForm::class => AgentHierarchieSaisieFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentHierarchieCalculHydrator::class => AgentHierarchieCalculHydratorFactory::class,
            AgentHierarchieImportationHydrator::class => AgentHierarchieImportationHydratorFactory::class,
            AgentHierarchieSaisieHydrator::class => AgentHierarchieSaisieHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agentAutorite' => AgentAutoriteViewHelper::class,
            'agentSuperieur' => AgentSuperieurViewHelper::class,
        ],
    ]

];