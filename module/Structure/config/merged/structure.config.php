<?php

namespace Structure;

use Structure\Assertion\StructureAssertion;
use Structure\Assertion\StructureAssertionFactory;
use Structure\Controller\StructureController;
use Structure\Controller\StructureControllerFactory;
use Structure\Form\AjouterGestionnaire\AjouterGestionnaireForm;
use Structure\Form\AjouterGestionnaire\AjouterGestionnaireFormFactory;
use Structure\Provider\Privilege\StructurePrivileges;
use Structure\Service\Structure\StructureService;
use Structure\Service\Structure\StructureServiceFactory;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceFactory;
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
                            StructurePrivileges::STRUCTURE_COMPLEMENT_AGENT,
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
                        'extraction-listing-fiche-poste',
                        'extraction-listing-mission-specifique',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_AFFICHER,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'ajouter-fiche-poste-recrutement',
                        'dupliquer-fiche-poste-recrutement',
                    ],
                    'privileges' => StructurePrivileges::STRUCTURE_DESCRIPTION,
                ],
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'rechercher',
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
                [
                    'controller' => StructureController::class,
                    'action' => [
                        'ajouter-manuellement-agent',
                        'retirer-manuellement-agent',
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
                                'order'    => 110,
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
                    'ajouter-fiche-poste-recrutement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-fiche-poste-recrutement/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-fiche-poste-recrutement',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'dupliquer-fiche-poste-recrutement' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dupliquer-fiche-poste-recrutement/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'dupliquer-fiche-poste-recrutement',
                            ],
                        ],
                        'may_terminate' => true,
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
                    'ajouter-manuellement-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-manuellement-agent/:structure',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'ajouter-manuellement-agent',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [],
                    ],
                    'retirer-manuellement-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-manuellement-agent/:structure/:agent',
                            'defaults' => [
                                'controller' => StructureController::class,
                                'action'     => 'retirer-manuellement-agent',
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

                    /** Extraction  */
                    'extraction' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/extraction',
                            'defaults' => [
                                'controller' => StructureController::class,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'listing-fiche-poste' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/listing-fiche-poste/:structure',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'extraction-listing-fiche-poste',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [],
                            ],
                            'listing-mission-specifique' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/listing-mission-specifique/:structure',
                                    'defaults' => [
                                        'controller' => StructureController::class,
                                        'action'     => 'extraction-listing-mission-specifique',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'service_manager' => [
        'factories' => [
            StructureService::class => StructureServiceFactory::class,
            StructureAgentForceService::class => StructureAgentForceServiceFactory::class,

            StructureAssertion::class => StructureAssertionFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            StructureController::class => StructureControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AjouterGestionnaireForm::class => AjouterGestionnaireFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [],
    ]

];