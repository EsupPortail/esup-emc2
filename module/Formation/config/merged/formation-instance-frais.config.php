<?php

namespace Formation;

use Formation\Controller\FormationInstanceFraisController;
use Formation\Controller\FormationInstanceFraisControllerFactory;
use Formation\Form\FormationInstanceFrais\FormationInstanceFraisForm;
use Formation\Form\FormationInstanceFrais\FormationInstanceFraisFormFactory;
use Formation\Form\FormationInstanceFrais\FormationInstanceFraisHydrator;
use Formation\Form\FormationInstanceFrais\FormationInstanceFraisHydratorFactory;
use Formation\Provider\Privilege\FormationinstancefraisPrivileges;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceFraisController::class,
                    'action' => [
                        'renseigner-frais',
                    ],
                    'privileges' => [
                        FormationinstancefraisPrivileges::FORMATIONINSTANCEFRAIS_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation-instance' => [
                'child_routes' => [
                    'renseigner-frais' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/renseigner-frais/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceFraisController::class,
                                'action'     => 'renseigner-frais',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceFraisService::class => FormationInstanceFraisServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstanceFraisController::class => FormationInstanceFraisControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationInstanceFraisForm::class => FormationInstanceFraisFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationInstanceFraisHydrator::class => FormationInstanceFraisHydratorFactory::class,
        ],
    ]

];