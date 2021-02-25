<?php

namespace UnicaenGlossaire;

use UnicaenGlossaire\Controller\DefinitionController;
use UnicaenGlossaire\Controller\DefinitionControllerFactory;
use UnicaenGlossaire\Form\Definition\DefinitionForm;
use UnicaenGlossaire\Form\Definition\DefinitionFormFactory;
use UnicaenGlossaire\Form\Definition\DefinitionHydrator;
use UnicaenGlossaire\Form\Definition\DefinitionHydratorFactory;
use UnicaenGlossaire\Provider\Privilege\DefinitionPrivileges;
use UnicaenGlossaire\Service\Definition\DefinitionService;
use UnicaenGlossaire\Service\Definition\DefinitionServiceFactory;
use UnicaenGlossaire\View\Helper\DictionnaireGenerationViewHelper;
use UnicaenGlossaire\View\Helper\DictionnaireGenerationViewHelperFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DefinitionController::class,
                    'action' => [
                        'index',
                    ],
                    'pivileges' => DefinitionPrivileges::DEFINITION_INDEX,
                ],
                [
                    'controller' => DefinitionController::class,
                    'action' => [
                        'afficher',
                    ],
                    'pivileges' => DefinitionPrivileges::DEFINITION_AFFICHER,
                ],
                [
                    'controller' => DefinitionController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'pivileges' => DefinitionPrivileges::DEFINITION_AJOUTER,
                ],
                [
                    'controller' => DefinitionController::class,
                    'action' => [
                        'modifier',
                    ],
                    'pivileges' => DefinitionPrivileges::DEFINITION_MODIFIER,
                ],
                [
                    'controller' => DefinitionController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'pivileges' => DefinitionPrivileges::DEFINITION_HISTORISER,
                ],
                [
                    'controller' => DefinitionController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'pivileges' => DefinitionPrivileges::DEFINITION_SUPPRIMER,
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'definition' => [
                            'label'    => 'Glossaire',
                            'route'    => 'definition',
//                            'resource' => PrivilegeController::getResourceId(DefinitionController::class, 'index'),
                            'resource' => DefinitionPrivileges::getResourceId(DefinitionPrivileges::DEFINITION_INDEX),
                            'order'    => 10000,
                            'pages' => [],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'definition' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/definition',
                    'defaults' => [
                        'controller' => DefinitionController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:definition',
                            'defaults' => [
                                'controller' => DefinitionController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => DefinitionController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:definition',
                            'defaults' => [
                                'controller' => DefinitionController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:definition',
                            'defaults' => [
                                'controller' => DefinitionController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:definition',
                            'defaults' => [
                                'controller' => DefinitionController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:definition',
                            'defaults' => [
                                'controller' => DefinitionController::class,
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
            DefinitionService::class => DefinitionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            DefinitionController::class => DefinitionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            DefinitionForm::class => DefinitionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            DefinitionHydrator::class => DefinitionHydratorFactory::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            DictionnaireGenerationViewHelper::class => DictionnaireGenerationViewHelperFactory::class,
        ],
        'aliases' => [
            'dictionnaireGeneration' => DictionnaireGenerationViewHelper::class,
        ],
    ],
];