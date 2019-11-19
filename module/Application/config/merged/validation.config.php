<?php

namespace Application;

use Application\Controller\ValidationController;
use Application\Controller\ValidationControllerFactory;
use Application\Form\Validation\ValidationForm;
use Application\Form\Validation\ValidationFormFactory;
use Application\Form\Validation\ValidationHydrator;
use Application\Form\Validation\ValidationHydratorFactory;
use Application\Form\ValidationDemande\ValidationDemandeForm;
use Application\Form\ValidationDemande\ValidationDemandeFormFactory;
use Application\Form\ValidationDemande\ValidationDemandeHydrator;
use Application\Form\ValidationDemande\ValidationDemandeHydratorFactory;
use Application\Provider\Privilege\ValidationPrivileges;
use Application\Service\Validation\ValidationDemandeService;
use Application\Service\Validation\ValidationDemandeServiceFactory;
use Application\Service\Validation\ValidationService;
use Application\Service\Validation\ValidationServiceFactory;
use Application\Service\Validation\ValidationTypeService;
use Application\Service\Validation\ValidationTypeServiceFactory;
use Application\Service\Validation\ValidationValeurService;
use Application\Service\Validation\ValidationValeurServiceFactory;
use Application\View\Helper\ValidationBadgeViewHelper;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => ValidationController::class,
                    'action' => [
                        'index',
                        'creer',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'afficher',
                        'creer-demandes-fiche-metier-domaine',
                        'creer-demande-fiche-metier',
                        'modifier-demande',
                        'detruire-demande',
                        'redux',
                    ],
//                    'privileges' => [
//                        AdministrationPrivileges::AFFICHER,
//                    ],
                    'roles' => [ 'guest' ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'validation' => [
                'type'  => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/validation',
                    'defaults' => [
                        'controller' => ValidationController::class,
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'redux' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/redux/:validation',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'redux',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/afficher/:validation',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'creer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer/:type/:objectId',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'creer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier/:validation',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'creer-demande-fiche-metier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer-demande-fiche-metier',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'creer-demande-fiche-metier',
                            ],
                        ],
                    ],
                    'creer-demandes-fiche-metier-domaine' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/creer-demandes-fiche-metier-validateur-domaine',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'creer-demandes-fiche-metier-domaine',
                            ],
                        ],
                    ],
                    'modifier-demande' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier-demande/:demande',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'modifier-demande',
                            ],
                        ],
                    ],
                    'detruire-demande' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/detruire-demande/:demande',
                            'defaults' => [
                                'controller' => ValidationController::class,
                                'action'     => 'detruire-demande',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'service_manager' => [
        'factories' => [
            ValidationDemandeService::class =>  ValidationDemandeServiceFactory::class,
            ValidationService::class =>  ValidationServiceFactory::class,
            ValidationTypeService::class => ValidationTypeServiceFactory::class,
            ValidationValeurService::class => ValidationValeurServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            ValidationController::class => ValidationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ValidationForm::class => ValidationFormFactory::class,
            ValidationDemandeForm::class => ValidationDemandeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ValidationHydrator::class => ValidationHydratorFactory::class,
            ValidationDemandeHydrator::class => ValidationDemandeHydratorFactory::class,
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'validationBadge' => ValidationBadgeViewHelper::class,
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'validations' => [
                                'label'    => 'Validations',
                                'route'    => 'validation',
                                'resource' => ValidationPrivileges::getResourceId(ValidationPrivileges::AFFICHER),
                                'order'    => 1111,
                                'icon' => 'fas fa-certificate',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];