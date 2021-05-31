<?php

namespace Formation;

use Formation\Controller\FormationInstanceController;
use Formation\Controller\FormationInstanceControllerFactory;
use Formation\Form\FormationInstance\FormationInstanceForm;
use Formation\Form\FormationInstance\FormationInstanceFormFactory;
use Formation\Form\FormationInstance\FormationInstanceHydrator;
use Formation\Form\FormationInstance\FormationInstanceHydratorFactory;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstance\FormationInstanceServiceFactory;
use Formation\View\Helper\FormationInstanceInformationsViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Mvc\Console\Router\Simple;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'modifier-informations',
                        'export-emargement',
                        'export-tous-emargements',

                        'renseigner-questionnaire',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'ouvrir-inscription',
                        'fermer-inscription',
                        'envoyer-convocation',
                        'demander-retour',
                        'cloturer',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'restaurer',
                        'historiser',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_HISTORISER,
                    ],
                ],
                [
                    'controller' => FormationInstanceController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_SUPPRIMER,
                    ],
                ],
                //console
//                [
//                    'controller' => FormationInstanceController::class,
//                    'action' => [
//                        'formation-console',
//                    ],
//                    'roles' => [],
//                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formation-instance' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/formation-instance',
                    'defaults' => [
                        'controller' => FormationInstanceController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'ajouter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter/:formation',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-informations' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-informations/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'modifier-informations',
                            ],
                        ],
                    ],
                    'renseigner-questionnaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/renseigner-questionnaire/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'renseigner-questionnaire',
                            ],
                        ],
                    ],
                    'ouvrir-inscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ouvrir-inscription/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'ouvrir-inscription',
                            ],
                        ],
                    ],
                    'fermer-inscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/fermer-inscription/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'fermer-inscription',
                            ],
                        ],
                    ],
                    'envoyer-convocation' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-convocation/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'envoyer-convocation',
                            ],
                        ],
                    ],
                    'demander-retour' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/demander-retour/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'demander-retour',
                            ],
                        ],
                    ],
                    'cloturer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/cloturer/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceController::class,
                                'action'     => 'cloturer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'formation-console' => [
                    'type' => Simple::class,
                    'options' => [
                        'route' => 'formation-console',
                        'defaults' => [
                            'controller' => FormationInstanceController::class,
                            'action' => 'formation-console'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceService::class => FormationInstanceServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstanceController::class => FormationInstanceControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            FormationInstanceForm::class => FormationInstanceFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            FormationInstanceHydrator::class => FormationInstanceHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formationInstanceInformations' => FormationInstanceInformationsViewHelper::class,
        ],
    ],

];