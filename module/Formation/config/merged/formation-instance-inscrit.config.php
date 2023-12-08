<?php

namespace Formation;

use Formation\Controller\FormationInstanceInscritController;
use Formation\Controller\FormationInstanceInscritControllerFactory;
use Formation\Controller\PlanDeFormationController;
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

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FormationInstanceInscritController::class,
                    'action' => [
                        'afficher-agent',
                        'ajouter-agent',
                        'ajouter-stagiaire-externe',
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
                        'inscription-formation',
                        'inscription',
                        'desinscription',

                        'formations',
                        'inscriptions',
                        'inscription-interne',
                        'inscription-externe',
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
        'formation' => [
            'home' => [
                'pages' => [
                    [
                        'order' => 100,
                        'label' => "Plan de formation",
                        'route' => 'plan-de-formation/courant',
                        'resource' => PrivilegeController::getResourceId(PlanDeFormationController::class, 'courant') ,
                    ],
                    [
                        'order' => 200,
                        'label' => "M'inscrire",
                        'route' => 'home',
                        'resources' => [
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-interne'),
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-externe'),
                        ],
                        'dropdown-header' => true,
                        'pages' => [
                            [
                                'order' => 310,
                                'label' => 'Formation du plan de formation',
                                'route' => 'inscription-interne',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-interne'),
                            ],
                            [
                                'order' => 320,
                                'label' => 'Stage hors plan de formation',
                                'route' => 'inscription-externe',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscription-externe'),
                            ],
                        ],
                    ],
                    [
                        'order' => 300,
                        'label' => 'Mes formations',
                        'title' => 'Mes formations choucroute',
                        'route' => 'home',
                        'resources' => [
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscriptions'),
                            PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'formations'),
                        ],
                        'dropdown-header' => true,
                        'pages' => [
                            [
                                'order' => 310,
                                'label' => 'Mes inscriptions en cours',
                                'route' => 'inscriptions',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'inscriptions'),
                            ],
                            [
                                'order' => 320,
                                'label' => 'Mes formations réalisées',
                                'route' => 'formations',
                                'resource' => PrivilegeController::getResourceId(FormationInstanceInscritController::class, 'formations'),
                            ],
                        ],
                    ],
                    [
                        'order' => 400,
                        'label' => 'Mon projet personnel',
                        'route' => 'projet-personnel',
                        'resource' => PrivilegeController::getResourceId(ProjetPersonnelController::class, 'index'),
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formations' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/mes-formations[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'formations',
                    ],
                ],
            ],
            'inscriptions' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/mes-inscriptions[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'inscriptions',
                    ],
                ],
            ],
            'inscription-interne' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/inscription-interne[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'inscription-interne',
                    ],
                ],
            ],
            'inscription-externe' => [
                'type'  => Segment::class,
                'options' => [
                    'route'    => '/inscription-externe[/:agent]',
                    'defaults' => [
                        'controller' => FormationInstanceInscritController::class,
                        'action'     => 'inscription-externe',
                    ],
                ],
            ],
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
                    'ajouter-stagiaire-externe' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/ajouter-stagiaire-externe/:session',
                            'defaults' => [
                                /** @see FormationInstanceInscritController::ajouterStagiaireExterneAction() */
                                'action'     => 'ajouter-stagiaire-externe',
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