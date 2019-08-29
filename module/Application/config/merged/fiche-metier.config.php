<?php

namespace Application;

use Application\Controller\FicheMetier\FicheMetierController;
use Application\Controller\FicheMetier\FicheMetierControllerFactory;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\ActiviteExistanteFormFactory;
use Application\Form\FicheMetier\ApplicationsForm;
use Application\Form\FicheMetier\ApplicationsFormFactory;
use Application\Form\FicheMetier\ApplicationsHydrator;
use Application\Form\FicheMetier\ApplicationsHydratorFactory;
use Application\Form\FicheMetier\FormationBaseForm;
use Application\Form\FicheMetier\FormationBaseFormFactory;
use Application\Form\FicheMetier\FormationBaseHydrator;
use Application\Form\FicheMetier\FormationComportementaleForm;
use Application\Form\FicheMetier\FormationComportementaleFormFactory;
use Application\Form\FicheMetier\FormationComportementaleHydrator;
use Application\Form\FicheMetier\FormationOperationnelleForm;
use Application\Form\FicheMetier\FormationOperationnelleFormFactory;
use Application\Form\FicheMetier\FormationOperationnelleHydrator;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\FicheMetier\LibelleFormFactory;
use Application\Form\FicheMetier\LibelleHydrator;
use Application\Form\FicheMetier\LibelleHydratorFactory;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetier\FicheMetierServiceFactory;
use Application\View\Helper\FicheMetierExterneViewHelper;
use Application\View\Helper\FicheMetierViewHelper;
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
                        'editer',
                        'detruire',
                        'historiser',
                        'restaurer',
                        'export',

                        'editer-libelle',
                        'retirer-activite',
                        'deplacer-activite',
                        'ajouter-nouvelle-activite',
                        'ajouter-activite-existante',

                        'modifier-connaissances',
                        'modifier-operationnelle',
                        'modifier-comportementale',
                        'modifier-application',
                        'ajouter',
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
            'fiche-metier-type' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier-type',
                    'defaults' => [
                        'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
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
                    'export' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/export/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'export',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer[/:id]',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer',
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
                        'may_terminate' => true,
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
                        'may_terminate' => true,
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/detruire/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'editer-libelle' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/editer-libelle/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
                                'action'     => 'editer-libelle',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'retirer-activite' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/retirer-activite/:id',
                            'defaults' => [
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
                                'controller' => FicheMetierController::class,
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
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
        'factories' => [
            ActiviteExistanteForm::class => ActiviteExistanteFormFactory::class,
            LibelleForm::class => LibelleFormFactory::class,

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
            LibelleHydrator::class => LibelleHydratorFactory::class,
            ApplicationsHydrator::class => ApplicationsHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'specificitePoste' => SpecificitePosteViewHelper::class,
            'ficheMetierExterne' => FicheMetierExterneViewHelper::class,
            'ficheMetier'  => FicheMetierViewHelper::class,
        ],
    ],

];