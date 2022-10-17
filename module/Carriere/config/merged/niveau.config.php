<?php

namespace Carriere;

use Carriere\Controller\NiveauController;
use Carriere\Controller\NiveauControllerFactory;
use Carriere\Form\ModifierNiveau\ModifierNiveauForm;
use Carriere\Form\ModifierNiveau\ModifierNiveauFormFactory;
use Carriere\Form\ModifierNiveau\ModifierNiveauHydrator;
use Carriere\Form\ModifierNiveau\ModifierNiveauHydratorFactory;
use Carriere\Form\Niveau\NiveauForm;
use Carriere\Form\Niveau\NiveauFormFactory;
use Carriere\Form\Niveau\NiveauHydrator;
use Carriere\Form\Niveau\NiveauHydratorFactory;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormFactory;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeHydrator;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeHydratorFactory;
use Carriere\Provider\Privilege\CorpsPrivileges;
use Carriere\Service\Niveau\NiveauService;
use Carriere\Service\Niveau\NiveauServiceFactory;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceFactory;
use Carriere\View\Helper\NiveauEnveloppeViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => NiveauController::class,
                    'action' => [
                        'index',
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                    ],
                    'privileges' => [
                        CorpsPrivileges::CORPS_AFFICHER,
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
                            'niveau' => [
                                'label'    => 'Niveaux',
                                'route'    => 'niveau',
                                'resource' => PrivilegeController::getResourceId(NiveauController::class, 'index') ,
                                'order'    => 840,
                                'pages' => [],
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
            'niveau' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/niveau',
                    'defaults' => [
                        'controller' => NiveauController::class,
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
                                'controller' => NiveauController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:niveau',
                            'defaults' => [
                                'controller' => NiveauController::class,
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
            NiveauService::class => NiveauServiceFactory::class,
            NiveauEnveloppeService::class => NiveauEnveloppeServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            NiveauController::class => NiveauControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ModifierNiveauForm::class => ModifierNiveauFormFactory::class,
            NiveauForm::class => NiveauFormFactory::class,
            NiveauEnveloppeForm::class => NiveauEnveloppeFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ModifierNiveauHydrator::class => ModifierNiveauHydratorFactory::class,
            NiveauHydrator::class => NiveauHydratorFactory::class,
            NiveauEnveloppeHydrator::class => NiveauEnveloppeHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'niveauEnveloppe' => NiveauEnveloppeViewHelper::class,
        ],
    ],
];