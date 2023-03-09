<?php

namespace Metier;

use Metier\Controller\DomaineController;
use Metier\Controller\DomaineControllerFactory;
use Metier\Form\Domaine\DomaineForm;
use Metier\Form\Domaine\DomaineFormFactory;
use Metier\Form\Domaine\DomaineHydrator;
use Metier\Form\Domaine\DomaineHydratorFactory;
use Metier\Form\SelectionnerDomaines\SelectionnerDomainesForm;
use Metier\Form\SelectionnerDomaines\SelectionnerDomainesFormFactory;
use Metier\Form\SelectionnerDomaines\SelectionnerDomainesHydrator;
use Metier\Form\SelectionnerDomaines\SelectionnerDomainesHydratorFactory;
use Metier\Provider\Privilege\DomainePrivileges;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Domaine\DomaineServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        DomainePrivileges::DOMAINE_AFFICHER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        DomainePrivileges::DOMAINE_AJOUTER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        DomainePrivileges::DOMAINE_MODIFIER
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        DomainePrivileges::DOMAINE_HISTORISER
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        DomainePrivileges::DOMAINE_SUPPRIMER
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'domaine' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/domaine',
                    'defaults' => [
                        'controller' => DomaineController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [

                    /** DOMAINE ***************************************************************************************/

                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'controller' => DomaineController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:domaine',
                            'defaults' => [
                                'controller' => DomaineController::class,
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:domaine',
                            'defaults' => [
                                'controller' => DomaineController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:domaine',
                            'defaults' => [
                                'controller' => DomaineController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'effacer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/effacer/:domaine',
                            'defaults' => [
                                'controller' => DomaineController::class,
                                'action'     => 'effacer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            DomaineService::class => DomaineServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            DomaineController::class => DomaineControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            DomaineForm::class => DomaineFormFactory::class,
            SelectionnerDomainesForm::class => SelectionnerDomainesFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            DomaineHydrator::class => DomaineHydratorFactory::class,
            SelectionnerDomainesHydrator::class => SelectionnerDomainesHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
];
