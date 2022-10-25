<?php

namespace Formation;

use Formation\Controller\FormationInstanceInscritController;
use Formation\Controller\FormationInstanceInscritControllerFactory;
use Formation\Controller\PlanFormationController;
use Formation\Controller\ProjetPersonnelController;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Form\Inscription\InscriptionFormFactory;
use Formation\Form\Inscription\InscriptionHydrator;
use Formation\Form\Inscription\InscriptionHydratorFactory;
use Formation\Provider\Privilege\FormationinstanceinscritPrivileges;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

$annee = '2023';

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'afficher-agent',
                        'ajouter-agent',
                        'historiser-agent',
                        'restaurer-agent',
                        'supprimer-agent',
                        'envoyer-liste-principale',
                        'envoyer-liste-complementaire',
                        'classer-inscription',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'liste-formations-instances',
                        'inscription-formation',
                        'inscription',
                        'desinscription',
                    ],
                    'roles' => [
                        'Agent',
                    ],
                ],
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'valider-responsable',
                        'refuser-responsable',
                    ],
                    'privileges' => [
                        FormationinstanceinscritPrivileges::INSCRIPTION_VALIDER_SUPERIEURE,
                    ],
                ],
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'valider-drh',
                        'refuser-drh',
                    ],
                    'privileges' => [
                        FormationinstanceinscritPrivileges::INSCRIPTION_VALIDER_GESTIONNAIRE,
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'formations' => [
                        'order' => 1000,
                        'label' => 'Formations',
                        'title' => "Gestion des actions de formations et des inscriptions à celles-ci",
                        'route' => 'liste-formations-instances',
                        'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'liste-formations-instances'),
                        'pages' => [
                            [
                                'order' => 200,
                                'label' => 'Plan de formation '. $annee,
                                'route' => 'plan-formation',
                                'resource' => PrivilegeController::getResourceId(PlanFormationController::class, 'afficher') ,
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 405,
                                'label' => "S'inscrire à une formation",
                                'route' => 'inscription-formation',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-formation'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 405,
                                'label' => 'Mes formations',
                                'route' => 'liste-formations-instances',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'liste-formations-instances'),
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 410,
                                'label' => 'Mon projet personnel',
                                'route' => 'projet-personnel',
                                'resource' => PrivilegeController::getResourceId(ProjetPersonnelController::class, 'index'),
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
            'inscription-formation' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/inscription-formation',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'inscription-formation',
                    ],
                ],
            ],
            'liste-formations-instances' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/liste-formations-instances',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'liste-formations-instances',
                    ],
                ],
            ],
            'formation-instance' => [
                'child_routes' => [
                    'inscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/inscription/:formation-instance/:agent',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'inscription',
                            ],
                        ],
                    ],
                    'desinscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/desinscription/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'desinscription',
                            ],
                        ],
                    ],
                    'valider-responsable' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/valider-responsable/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'valider-responsable',
                            ],
                        ],
                    ],
                    'refuser-responsable' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/refuser-responsable/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'refuser-responsable',
                            ],
                        ],
                    ],
                    'valider-drh' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/valider-drh/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'valider-drh',
                            ],
                        ],
                    ],
                    'refuser-drh' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/refuser-drh/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'refuser-drh',
                            ],
                        ],
                    ],
                    'ajouter-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-agent/:formation-instance',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'ajouter-agent',
                            ],
                        ],
                    ],
                    'afficher-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'afficher-agent',
                            ],
                        ],
                    ],
                    'historiser-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'historiser-agent',
                            ],
                        ],
                    ],
                    'restaurer-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'restaurer-agent',
                            ],
                        ],
                    ],
                    'supprimer-agent' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer-agent/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'supprimer-agent',
                            ],
                        ],
                    ],
                    'envoyer-liste-principale' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-liste-principale/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'envoyer-liste-principale',
                            ],
                        ],
                    ],
                    'envoyer-liste-complementaire' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/envoyer-liste-complementaire/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'envoyer-liste-complementaire',
                            ],
                        ],
                    ],
                    'classer-inscription' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/classer-inscription/:inscrit',
                            'defaults' => [
                                'controller' => FormationInstanceInscritController::class,
                                'action'     => 'classer-inscription',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FormationInstanceInscritService::class => FormationInstanceInscritServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FormationInstanceInscritController::class => FormationInstanceInscritControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            InscriptionForm::class => InscriptionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            InscriptionHydrator::class => InscriptionHydratorFactory::class,
        ],
    ]

];