<?php

namespace Application;

use Application\Controller\FicheMetier\FicheMetierController;
use Application\Controller\FicheMetier\FicheMetierControllerFactory;
use Application\Controller\FicheMetier\FicheMetierTypeController;
use Application\Controller\FicheMetier\FicheMetierTypeControllerFactory;
use Application\Form\FicheMetier\AssocierAgentForm;
use Application\Form\FicheMetier\AssocierAgentFormFactory;
use Application\Form\FicheMetier\AssocierAgentHydrator;
use Application\Form\FicheMetier\AssocierAgentHydratorFactory;
use Application\Form\FicheMetier\AssocierMetierTypeForm;
use Application\Form\FicheMetier\AssocierMetierTypeFormFactory;
use Application\Form\FicheMetier\AssocierMetierTypeHydrator;
use Application\Form\FicheMetier\AssocierMetierTypeHydratorFactory;
use Application\Form\FicheMetier\AssocierPosteForm;
use Application\Form\FicheMetier\AssocierPosteFormFactory;
use Application\Form\FicheMetier\AssocierPosteHydrator;
use Application\Form\FicheMetier\AssocierPosteHydratorFactory;
use Application\Form\FicheMetier\FicheMetierCreationForm;
use Application\Form\FicheMetier\FicheMetierCreationFormFactory;
use Application\Form\FicheMetier\FicheMetierCreationHydrator;
use Application\Form\FicheMetier\FicheMetierCreationHydratorFactory;
use Application\Form\FicheMetier\SpecificitePosteForm;
use Application\Form\FicheMetier\SpecificitePosteFormFactory;
use Application\Form\FicheMetierType\ActiviteExistanteForm;
use Application\Form\FicheMetierType\ActiviteExistanteFormFactory;
use Application\Form\FicheMetierType\ApplicationsForm;
use Application\Form\FicheMetierType\ApplicationsFormFactory;
use Application\Form\FicheMetierType\ApplicationsHydrator;
use Application\Form\FicheMetierType\ApplicationsHydratorFactory;
use Application\Form\FicheMetierType\FormationBaseForm;
use Application\Form\FicheMetierType\FormationBaseFormFactory;
use Application\Form\FicheMetierType\FormationBaseHydrator;
use Application\Form\FicheMetierType\FormationComportementaleForm;
use Application\Form\FicheMetierType\FormationComportementaleFormFactory;
use Application\Form\FicheMetierType\FormationComportementaleHydrator;
use Application\Form\FicheMetierType\FormationOperationnelleForm;
use Application\Form\FicheMetierType\FormationOperationnelleFormFactory;
use Application\Form\FicheMetierType\FormationOperationnelleHydrator;
use Application\Form\FicheMetierType\LibelleForm;
use Application\Form\FicheMetierType\LibelleFormFactory;
use Application\Form\FicheMetierType\LibelleHydrator;
use Application\Form\FicheMetierType\LibelleHydratorFactory;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetier\FicheMetierServiceFactory;
use Application\View\Helper\SpecificitePosteViewHelper;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'afficher',
                        'editer-specificite-poste',
                        'associer-metier-type',
                        'associer-agent',
                        'associer-poste',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::HISTORISER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'editer',
                        'creer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::EDITER,
                    ],
                ],
                [
                    'controller' => FicheMetierTypeController::class,
                    'action' => [
                        'index',
                    ],
                    'roles' => [
                    ],
                ],
                [
                    'controller' => FicheMetierTypeController::class,
                    'action' => [
                        'afficher',
                        'editer-libelle',
                        'editer-missions-principales',
                        'retirer-activite',
                        'deplacer-activite',
                        'ajouter-nouvelle-activite',
                        'ajouter-activite-existante',

                        'modifier-connaissances',
                        'modifier-operationnelle',
                        'modifier-comportementale',
                        'modifier-application',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::AFFICHER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                    'defaults' => [
                        'controller' => FicheMetierController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'associer-agent' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/associer-agent/:fiche',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'associer-agent',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'associer-poste' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/associer-poste/:fiche',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'associer-poste',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'associer-metier-type' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/associer-metier-type/:fiche',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'associer-metier-type',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'specificite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/specificite/:fiche',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer-specificite-poste',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer/:id/:section',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                ],
            ],
            'fiche-metier-type' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier-type',
                    'defaults' => [
                        'controller' => FicheMetierTypeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer-libelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer-libelle/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'editer-libelle',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer-missions-principales' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer-missions-principales/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'editer-missions-principales',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'retirer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-activite/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'retirer-activite',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'deplacer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/deplacer-activite/:id/:direction',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'deplacer-activite',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-nouvelle-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-nouvelle-activite/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'ajouter-nouvelle-activite',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajouter-activite-existante' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-activite-existante/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'ajouter-activite-existante',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-connaissances' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-connaissances/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'modifier-connaissances',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-operationnelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-operationnelle/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'modifier-operationnelle',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-comportementale' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-comportementale/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'modifier-comportementale',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'modifier-application' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-application/:id',
                            'defaults' => [
                                'controller' => FicheMetierTypeController::class,
                                'action'     => 'modifier-application',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            FicheMetierService::class => FicheMetierServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
            FicheMetierTypeController::class => FicheMetierTypeControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
        'factories' => [
            ActiviteExistanteForm::class => ActiviteExistanteFormFactory::class,
            FicheMetierCreationForm::class => FicheMetierCreationFormFactory::class,
            LibelleForm::class => LibelleFormFactory::class,

            SpecificitePosteForm::class => SpecificitePosteFormFactory::class,
            AssocierMetierTypeForm::class => AssocierMetierTypeFormFactory::class,
            AssocierAgentForm::class => AssocierAgentFormFactory::class,
            AssocierPosteForm::class => AssocierPosteFormFactory::class,

            FormationBaseForm::class => FormationBaseFormFactory::class,
            FormationOperationnelleForm::class => FormationOperationnelleFormFactory::class,
            FormationComportementaleForm::class => FormationComportementaleFormFactory::class,
            ApplicationsForm::class => ApplicationsFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            FormationBaseHydrator::class => FormationBaseHydrator::class,
            FormationOperationnelleHydrator::class => FormationOperationnelleHydrator::class,
            FormationComportementaleHydrator::class => FormationComportementaleHydrator::class,

        ],
        'factories' => [
            FicheMetierCreationHydrator::class => FicheMetierCreationHydratorFactory::class,
            LibelleHydrator::class => LibelleHydratorFactory::class,
            AssocierMetierTypeHydrator::class => AssocierMetierTypeHydratorFactory::class,
            AssocierAgentHydrator::class => AssocierAgentHydratorFactory::class,
            AssocierPosteHydrator::class => AssocierPosteHydratorFactory::class,
            ApplicationsHydrator::class => ApplicationsHydratorFactory::class,
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'specificitePoste' => SpecificitePosteViewHelper::class,
        ],
    ],

];