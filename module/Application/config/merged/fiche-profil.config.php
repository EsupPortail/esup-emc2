<?php

namespace Application;

use Application\Controller\FicheProfilController;
use Application\Controller\FicheProfilControllerFactory;
use Application\Form\FicheProfil\FicheProfilForm;
use Application\Form\FicheProfil\FicheProfilFormFactory;
use Application\Form\FicheProfil\FicheProfilHydrator;
use Application\Form\FicheProfil\FicheProfilHydratorFactory;
use Application\Provider\Privilege\FicheprofilPrivileges;
use Application\Service\FicheProfil\FicheProfilService;
use Application\Service\FicheProfil\FicheProfilServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheProfilController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => FicheprofilPrivileges::FICHEPROFIL_AJOUTER,
                ],
                [
                    'controller' => FicheProfilController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => FicheprofilPrivileges::FICHEPROFIL_MODIFIER,
                ],
                [
                    'controller' => FicheProfilController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => FicheprofilPrivileges::FICHEPROFIL_HISTORISER,
                ],
                [
                    'controller' => FicheProfilController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => FicheprofilPrivileges::FICHEPROFIL_SUPPRIMER,
                ],
                [
                    'controller' => FicheProfilController::class,
                    'action' => [
                        'exporter',
                    ],
                    'privileges' => FicheprofilPrivileges::FICHEPROFIL_EXPORTER,
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-profil' => [
                'type'  => Literal::class,
                'may_terminate' => false,
                'options' => [
                    'route'    => '/fiche-profil',
                    'defaults' => [
                        'controller' => FicheProfilController::class,
                    ],
                ],
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/ajouter/:structure',
                            'defaults' => [
                                'controller' => FicheProfilController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier/:fiche-profil',
                            'defaults' => [
                                'controller' => FicheProfilController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/historiser/:fiche-profil',
                            'defaults' => [
                                'controller' => FicheProfilController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/restaurer/:fiche-profil',
                            'defaults' => [
                                'controller' => FicheProfilController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/supprimer/:fiche-profil',
                            'defaults' => [
                                'controller' => FicheProfilController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'exporter' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/exporter/:fiche-profil',
                            'defaults' => [
                                'controller' => FicheProfilController::class,
                                'action'     => 'exporter',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FicheProfilService::class => FicheProfilServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FicheProfilController::class => FicheProfilControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FicheProfilForm::class => FicheProfilFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FicheProfilHydrator::class => FicheProfilHydratorFactory::class,
        ],
    ]

];