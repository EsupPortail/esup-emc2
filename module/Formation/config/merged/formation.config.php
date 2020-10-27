<?php

namespace Formation;

use Formation\Assertion\FormationInstanceInscritAssertion;
use Formation\Assertion\FormationInstanceInscritAssertionFactory;
use Formation\Controller\FormationController;
use Formation\Controller\FormationControllerFactory;
use Formation\Form\Formation\FormationForm;
use Formation\Form\Formation\FormationFormFactory;
use Formation\Form\Formation\FormationHydrator;
use Formation\Form\Formation\FormationHydratorFactory;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Formation\Form\SelectionFormation\SelectionFormationFormFactory;
use Formation\Form\SelectionFormation\SelectionFormationHydrator;
use Formation\Form\SelectionFormation\SelectionFormationHydratorFactory;
use Formation\Provider\Privilege\FormationPrivileges;
use Formation\Service\Formation\FormationService;
use Formation\Service\Formation\FormationServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Inscrit' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            FormationPrivileges::FORMATION_QUESTIONNAIRE_VISUALISER,
                            FormationPrivileges::FORMATION_QUESTIONNAIRE_MODIFIER,
                        ],
                        'resources' => ['Inscrit'],
                        'assertion' => FormationInstanceInscritAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'editer',
                        'modifier-formation-informations',
                        'ajouter-instance',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        FormationPrivileges::FORMATION_SUPPRIMER,
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
                            'formation' => [
                                'label'    => 'Formations',
                                'route'    => 'formation',
                                'resource' => FormationPrivileges::getResourceId(FormationPrivileges::FORMATION_ACCES),
                                'order'    => 700,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation',
                    'defaults' => [
                        'controller' => FormationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'modifier-formation-informations' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-formation-informations/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'modifier-formation-informations',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:formation',
                            'defaults' => [
                                'controller' => FormationController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceInscritAssertion::class => FormationInstanceInscritAssertionFactory::class,
            FormationService::class => FormationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationController::class => FormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationForm::class => FormationFormFactory::class,
            SelectionFormationForm::class => SelectionFormationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationHydrator::class => FormationHydratorFactory::class,
            SelectionFormationHydrator::class => SelectionFormationHydratorFactory::class,
        ],
    ]

];