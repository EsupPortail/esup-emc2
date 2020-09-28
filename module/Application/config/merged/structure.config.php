<?php

namespace Application;

use Application\Assertion\StructureAssertion;
use Application\Assertion\StructureAssertionFactory;
use Application\Controller\StructureController;
use Application\Controller\StructureControllerFactory;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireForm;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireFormFactory;
use Application\Form\Structure\StructureForm;
use Application\Form\Structure\StructureFormFactory;
use Application\Form\Structure\StructureHydrator;
use Application\Provider\Privilege\StructurePrivileges;
use Application\Service\Structure\StructureService;
use Application\Service\Structure\StructureServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Structure' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            StructurePrivileges::STRUCTURE_AFFICHER,
                            StructurePrivileges::STRUCTURE_DESCRIPTION,
                            StructurePrivileges::STRUCTURE_GESTIONNAIRE,
                        ],
                        'resources' => ['Structure'],
                        'assertion' => StructureAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_INDEX,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'rechercher',
                        'rechercher-gestionnaires',
                        'rechercher-with-structure-mere',
                        'graphe',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                    'assertion'  => StructureAssertion::class,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'editer-description',
                        'toggle-resume-mere',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_DESCRIPTION,
                    'assertion'  => StructureAssertion::class,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'ajouter-gestionnaire',
                        'retirer-gestionnaire',
                        'ajouter-responsable',
                        'retirer-responsable',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_GESTIONNAIRE,
                    'assertion'  => StructureAssertion::class,

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
                            'structure' => [
                                'label' => 'Structures',
                                'route' => 'structure',
                                'resource' =>  StructurePrivileges::getResourceId(StructurePrivileges::STRUCTURE_AFFICHER) ,
                                'order'    => 1500,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'router'          => [
        'routes' => [
            'structure' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/structure',
                    'defaults' => [
                        'controller' => StructureController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'graphe' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/graphe[/:structure]',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'graphe',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'ajouter-gestionnaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-gestionnaire/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-gestionnaire',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'retirer-gestionnaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-gestionnaire/:structure/:gestionnaire',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'retirer-gestionnaire',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'ajouter-responsable' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-responsable/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-responsable',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'retirer-responsable' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-responsable/:structure/:responsable',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'retirer-responsable',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'editer-description' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer-description/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'editer-description',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'toggle-resume-mere' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/toggle-resume-mere/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'toggle-resume-mere',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    /** Fonctions de recherche de structures **********************************************************/
                    'rechercher' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'rechercher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'rechercher-gestionnaires' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/rechercher-gestionnaires/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'rechercher-gestionnaires',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'rechercher-with-structure-mere' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/rechercher-with-structure-mere/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'rechercher-with-structure-mere',
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
            StructureAssertion::class => StructureAssertionFactory::class,
            StructureService::class => StructureServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            StructureController::class => StructureControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            StructureForm::class => StructureFormFactory::class,
            AjouterGestionnaireForm::class => AjouterGestionnaireFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            StructureHydrator::class => StructureHydrator::class,
        ],
    ]

];