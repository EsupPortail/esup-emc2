<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\CampagneController;
use EntretienProfessionnel\Controller\CampagneControllerFactory;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Form\Campagne\CampagneFormFactory;
use EntretienProfessionnel\Form\Campagne\CampagneHydrator;
use EntretienProfessionnel\Form\Campagne\CampagneHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\CampagnePrivileges;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Campagne\CampagneServiceFactory;
use EntretienProfessionnel\View\Helper\CampagneAvancementViewHelper;
use EntretienProfessionnel\View\Helper\CampagneInformationViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'structure', //todo vrai privilege et assertion ...
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_HISTORISER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'campagne' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/campagne',
                            'defaults' => [
                                'controller' => CampagneController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher/:campagne',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'structure' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/structure/:campagne/:structure',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'structure',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:campagne',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:campagne',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:campagne',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire/:campagne',
                                    'defaults' => [
                                        'controller' => CampagneController::class,
                                        'action'     => 'detruire',
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
            CampagneService::class => CampagneServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CampagneController::class => CampagneControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CampagneForm::class => CampagneFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CampagneHydrator::class => CampagneHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'campagneInformation' => CampagneInformationViewHelper::class,
            'campagneAvancement' => CampagneAvancementViewHelper::class,
        ],
    ],

];