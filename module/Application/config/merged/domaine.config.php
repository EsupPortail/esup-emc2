<?php

namespace Application;

use Application\Controller\DomaineController;
use Application\Controller\DomaineControllerFactory;
use Application\Controller\MetierController;
use Application\Controller\MetierControllerFactory;
use Application\Form\Domaine\DomaineForm;
use Application\Form\Domaine\DomaineFormFactory;
use Application\Form\Domaine\DomaineHydrator;
use Application\Form\Domaine\DomaineHydratorFactory;
use Application\Form\Metier\MetierForm;
use Application\Form\Metier\MetierFormFactory;
use Application\Form\Metier\MetierHydrator;
use Application\Form\Metier\MetierHydratorFactory;
use Application\Form\MetierReference\MetierReferenceForm;
use Application\Form\MetierReference\MetierReferenceFormFactory;
use Application\Form\MetierReference\MetierReferenceHydrator;
use Application\Form\MetierReference\MetierReferenceHydraytorFactory;
use Application\Form\MetierReferentiel\MetierReferentielForm;
use Application\Form\MetierReferentiel\MetierReferentielFormFactory;
use Application\Form\MetierReferentiel\MetierReferentielHydrator;
use Application\Form\MetierReferentiel\MetierReferentielHydratorFactory;
use Application\Provider\Privilege\MetierPrivileges;
use Application\Service\Domaine\DomaineService;
use Application\Service\Domaine\DomaineServiceFactory;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceFactory;
use Application\Service\Metier\MetierService;
use Application\Service\Metier\MetierServiceFactory;
use Application\Service\MetierReference\MetierReferenceService;
use Application\Service\MetierReference\MetierReferenceServiceFactory;
use Application\Service\MetierReferentiel\MetierReferentielService;
use Application\Service\MetierReferentiel\MetierReferentielServiceFactory;
use Application\View\Helper\TypeFonctionViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => DomaineController::class,
                    'action' => [
                        'effacer',
                    ],
                    'privileges' => [
                        MetierPrivileges::METIER_DETRUIRE,
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
                    ],
                ],
                'may_terminate' => false,
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
        ],
    ],
    'hydrators' => [
        'factories' => [
            DomaineHydrator::class => DomaineHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
];
