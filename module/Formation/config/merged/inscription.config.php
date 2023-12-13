<?php

namespace Formation;

use Formation\Controller\InscriptionController;
use Formation\Controller\InscriptionControllerFactory;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Form\Inscription\InscriptionFormFactory;
use Formation\Form\Inscription\InscriptionHydrator;
use Formation\Form\Inscription\InscriptionHydratorFactory;
use Formation\Form\Justificatif\JustificatifForm;
use Formation\Form\Justificatif\JustificatifFormFactory;
use Formation\Form\Justificatif\JustificatifHydrator;
use Formation\Form\Justificatif\JustificatifHydratorFactory;
use Formation\Provider\Privilege\FormationinstanceinscritPrivileges;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\Inscription\InscriptionServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'index',
                        'afficher'
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'valider-responsable',
                        'refuser-responsable',
                    ],
                    'privileges' => [
                        FormationinstanceinscritPrivileges::INSCRIPTION_VALIDER_SUPERIEURE,
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'valider-drh',
                        'refuser-drh',
                        'classer',
                        'envoyer-liste-principale',
                        'envoyer-liste-complementaire',
                        'retirer-liste',
                    ],
                    'privileges' => [
                        FormationinstanceinscritPrivileges::INSCRIPTION_VALIDER_GESTIONNAIRE,
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'formation' => [
                'child_routes' => [
                    'inscription' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/inscription',
                            'defaults' => [
                                /** @see InscriptionController::indexAction() */
                                'controller' => InscriptionController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/afficher/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::afficherAction() */
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/ajouter[/:session]',
                                    'defaults' => [
                                        /** @see InscriptionController::afficherAction() */
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/modifier/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::modifierAction() */
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/historiser/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::historiserAction() */
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/restaurer/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::restaurerAction() */
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/supprimer/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::supprimerAction() */
                                        'action'     => 'supprimer',
                                    ],
                                ],
                            ],
                            /** Validation ****************************************************************************/
                            'valider-responsable' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/valider-responsable/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::validerResponsableAction() */
                                        'action'     => 'valider-responsable',
                                    ],
                                ],
                            ],
                            'refuser-responsable' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/refuser-responsable/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::refuserResponsablehAction() */
                                        'action'     => 'refuser-responsable',
                                    ],
                                ],
                            ],
                            'valider-drh' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/valider-drh/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::validerDrhAction() */
                                        'action'     => 'valider-drh',
                                    ],
                                ],
                            ],
                            'refuser-drh' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/refuser-drh/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::refuserDrhAction() */
                                        'action'     => 'refuser-drh',
                                    ],
                                ],
                            ],
                            /** Classement ****************************************************************************/
                            'envoyer-liste-principale' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/envoyer-liste-principale/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerListePrincipaleAction() */
                                        'action'     => 'envoyer-liste-principale',
                                    ],
                                ],
                            ],
                            'envoyer-liste-complementaire' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/envoyer-liste-complementaire/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerListeComplementaireAction() */
                                        'action'     => 'envoyer-liste-complementaire',
                                    ],
                                ],
                            ],
                            'retirer-liste' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/retirer-liste/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::retirerListeAction() */
                                        'action'     => 'retirer-liste',
                                    ],
                                ],
                            ],
                            'classer' => [
                                'type'  => Segment::class,
                                'options' => [
                                    'route'    => '/classer/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::classerAction() */
                                        'action'     => 'classer',
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
            InscriptionService::class => InscriptionServiceFactory::class
        ],
    ],
    'controllers'     => [
        'factories' => [
            InscriptionController::class => InscriptionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            InscriptionForm::class => InscriptionFormFactory::class,
            JustificatifForm::class => JustificatifFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            InscriptionHydrator::class => InscriptionHydratorFactory::class,
            JustificatifHydrator::class => JustificatifHydratorFactory::class,
        ],
    ]

];