<?php

namespace Formation;

use Formation\Controller\EnqueteQuestionController;
use Formation\Controller\EnqueteQuestionControllerFactory;
use Formation\Controller\EnqueteReponseController;
use Formation\Controller\EnqueteReponseControllerFactory;
use Formation\Form\EnqueteCategorie\EnqueteCategorieForm;
use Formation\Form\EnqueteCategorie\EnqueteCategorieFormFactory;
use Formation\Form\EnqueteCategorie\EnqueteCategorieHydrator;
use Formation\Form\EnqueteCategorie\EnqueteCategorieHydratorFactory;
use Formation\Form\EnqueteQuestion\EnqueteQuestionForm;
use Formation\Form\EnqueteQuestion\EnqueteQuestionFormFactory;
use Formation\Form\EnqueteQuestion\EnqueteQuestionHydrator;
use Formation\Form\EnqueteQuestion\EnqueteQuestionHydratorFactory;
use Formation\Form\EnqueteReponse\EnqueteReponseForm;
use Formation\Form\EnqueteReponse\EnqueteReponseFormFactory;
use Formation\Form\EnqueteReponse\EnqueteReponseHydrator;
use Formation\Form\EnqueteReponse\EnqueteReponseHydratorFactory;
use Formation\Provider\Privilege\FormationenquetePrivileges;
use Formation\Service\EnqueteCategorie\EnqueteCategorieService;
use Formation\Service\EnqueteCategorie\EnqueteCategorieServiceFactory;
use Formation\Service\EnqueteQuestion\EnqueteQuestionService;
use Formation\Service\EnqueteQuestion\EnqueteQuestionServiceFactory;
use Formation\Service\EnqueteReponse\EnqueteReponseService;
use Formation\Service\EnqueteReponse\EnqueteReponseServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => EnqueteQuestionController::class,
                    'action' => [
                        'afficher-questions',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_INDEX,
                    ],
                ],
                [
                    'controller' => EnqueteQuestionController::class,
                    'action' => [
                        'ajouter-categorie',
                        'ajouter-question',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_AJOUTER,
                    ],
                ],
                [
                    'controller' => EnqueteQuestionController::class,
                    'action' => [
                        'modifier-categorie',
                        'modifier-question',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_MODIFIER,
                    ],
                ],
                [
                    'controller' => EnqueteQuestionController::class,
                    'action' => [
                        'historiser-categorie',
                        'restaurer-categorie',
                        'historiser-question',
                        'restaurer-question',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_HISTORISER,
                    ],
                ],
                [
                    'controller' => EnqueteQuestionController::class,
                    'action' => [
                        'supprimer-categorie',
                        'supprimer-question',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => EnqueteQuestionController::class,
                    'action' => [
                        'repondre-questions',
                        'valider-questions',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_REPONDRE,
                    ],
                ],
                [
                    'controller' => EnqueteReponseController::class,
                    'action' => [
                        'afficher-resultats',
                    ],
                    'privileges' => [
                        FormationenquetePrivileges::ENQUETE_RESULTAT,
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'enquete' => [
                                'label'    => 'Enquête',
                                'route'    => 'formation/enquete/question',
                                'resource' => PrivilegeController::getResourceId(EnqueteQuestionController::class, 'afficher-questions') ,
                                'order'    => 430,
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
            'formation' => [
                'child_routes' => [
                    'enquete' => [
                        'type'  => Literal::class,
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/enquete',
                        ],
                        'child_routes' => [
                            'resultat' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/resultats[/:session]',
                                    'defaults' => [
                                        'controller' => EnqueteReponseController::class,
                                        'action'     => 'afficher-resultats',
                                    ],
                                ],
                            ],
                            'question' => [
                                'type'  => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/question',
                                    'defaults' => [
                                        'controller' => EnqueteQuestionController::class,
                                        'action'     => 'afficher-questions',
                                    ],
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type'  => Literal::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/ajouter',
                                            'defaults' => [
                                                'action'     => 'ajouter-question',
                                            ],
                                        ],
                                    ],
                                    'modifier' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/modifier/:question',
                                            'defaults' => [
                                                'action'     => 'modifier-question',
                                            ],
                                        ],
                                    ],
                                    'historiser' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/historiser/:question',
                                            'defaults' => [
                                                'action'     => 'historiser-question',
                                            ],
                                        ],
                                    ],
                                    'restaurer' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/restaurer/:question',
                                            'defaults' => [
                                                'action'     => 'restaurer-question',
                                            ],
                                        ],
                                    ],
                                    'supprimer' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/supprimer/:question',
                                            'defaults' => [
                                                'action'     => 'supprimer-question',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'categorie' => [
                                'type'  => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/categorie',
                                    'defaults' => [
                                        'controller' => EnqueteQuestionController::class,
                                    ],
                                ],
                                'child_routes' => [
                                    'ajouter' => [
                                        'type'  => Literal::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/ajouter',
                                            'defaults' => [
                                                'action'     => 'ajouter-categorie',
                                            ],
                                        ],
                                    ],
                                    'modifier' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/modifier/:categorie',
                                            'defaults' => [
                                                'action'     => 'modifier-categorie',
                                            ],
                                        ],
                                    ],
                                    'historiser' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/historiser/:categorie',
                                            'defaults' => [
                                                'action'     => 'historiser-categorie',
                                            ],
                                        ],
                                    ],
                                    'restaurer' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/restaurer/:categorie',
                                            'defaults' => [
                                                'action'     => 'restaurer-categorie',
                                            ],
                                        ],
                                    ],
                                    'supprimer' => [
                                        'type'  => Segment::class,
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/supprimer/:categorie',
                                            'defaults' => [
                                                'action'     => 'supprimer-categorie',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'repondre-questions' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/repondre-questions/:inscrit',
                                    'defaults' => [
                                        'controller' => EnqueteQuestionController::class,
                                        'action'     => 'repondre-questions',
                                    ],
                                ],
                            ],
                            'valider-questions' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/valider-questions/:inscrit',
                                    'defaults' => [
                                        'controller' => EnqueteQuestionController::class,
                                        'action'     => 'valider-questions',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            EnqueteCategorieService::class => EnqueteCategorieServiceFactory::class,
            EnqueteQuestionService::class => EnqueteQuestionServiceFactory::class,
            EnqueteReponseService::class => EnqueteReponseServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            EnqueteQuestionController::class => EnqueteQuestionControllerFactory::class,
            EnqueteReponseController::class => EnqueteReponseControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            EnqueteCategorieForm::class => EnqueteCategorieFormFactory::class,
            EnqueteQuestionForm::class => EnqueteQuestionFormFactory::class,
            EnqueteReponseForm::class => EnqueteReponseFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            EnqueteCategorieHydrator::class => EnqueteCategorieHydratorFactory::class,
            EnqueteQuestionHydrator::class => EnqueteQuestionHydratorFactory::class,
            EnqueteReponseHydrator::class => EnqueteReponseHydratorFactory::class,
        ],
    ]

];