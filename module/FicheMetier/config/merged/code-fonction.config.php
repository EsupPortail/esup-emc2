<?php

namespace FicheMetier;

use FicheMetier\Assertion\CodeFonctionAssertion;
use FicheMetier\Assertion\CodeFonctionAssertionFactory;
use FicheMetier\Controller\CodeFonctionController;
use FicheMetier\Controller\CodeFonctionControllerFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Form\CodeFonction\CodeFonctionFormFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionHydrator;
use FicheMetier\Form\CodeFonction\CodeFonctionHydratorFactory;
use FicheMetier\Provider\Privilege\CodeFonctionPrivileges;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'CodeFonction' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            CodeFonctionPrivileges::CODEFONCTION_INDEX,
                        ],
                        'resources' => ['CodeFonction'],
                        'assertion' => CodeFonctionAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        CodeFonctionPrivileges::CODEFONCTION_INDEX,
                    ],
                    'assertion' => CodeFonctionAssertion::class,
                ],
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        CodeFonctionPrivileges::CODEFONCTION_AFFICHER,
                    ],
                ],
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CodeFonctionPrivileges::CODEFONCTION_AJOUTER,
                    ],
                ],
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CodeFonctionPrivileges::CODEFONCTION_MODIFIER,
                    ],
                ],
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CodeFonctionPrivileges::CODEFONCTION_HISTORISER,
                    ],
                ],
                [
                    'controller' => CodeFonctionController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        CodeFonctionPrivileges::CODEFONCTION_SUPPRIMER,
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
                            'code-fonction' => [
                                'label' => 'Code fonction',
                                'route' => 'code-fonction',
                                'resource' => PrivilegeController::getResourceId(CodeFonctionController::class, 'index'),
                                'order' => 2502,
                                'pages' => [],
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
            'code-fonction' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/code-fonction',
                    'defaults' => [
                        /** @see CodeFonctionController::indexAction() */
                        'controller' => CodeFonctionController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:code-fonction',
                            'defaults' => [
                                /** @see CodeFonctionController::afficherAction() */
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                /** @see CodeFonctionController::ajouterAction() */
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:code-fonction',
                            'defaults' => [
                                /** @see CodeFonctionController::modifierAction() */
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:code-fonction',
                            'defaults' => [
                                /** @see CodeFonctionController::historiserAction() */
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:code-fonction',
                            'defaults' => [
                                /** @see CodeFonctionController::restaurerAction() */
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:code-fonction',
                            'defaults' => [
                                /** @see CodeFonctionController::supprimerAction() */
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CodeFonctionAssertion::class => CodeFonctionAssertionFactory::class,
            CodeFonctionService::class => CodeFonctionServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            CodeFonctionController::class => CodeFonctionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CodeFonctionForm::class => CodeFonctionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CodeFonctionHydrator::class => CodeFonctionHydratorFactory::class,
        ],
    ],

];