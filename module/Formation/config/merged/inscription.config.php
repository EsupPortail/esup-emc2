<?php

namespace Formation;

use Formation\Assertion\InscriptionAssertion;
use Formation\Assertion\InscriptionAssertionFactory;
use Formation\Controller\FormationController;
use Formation\Controller\InscriptionController;
use Formation\Controller\InscriptionControllerFactory;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Form\Inscription\InscriptionFormFactory;
use Formation\Form\Inscription\InscriptionHydrator;
use Formation\Form\Inscription\InscriptionHydratorFactory;
use Formation\Form\InscriptionFrais\InscriptionFraisForm;
use Formation\Form\InscriptionFrais\InscriptionFraisFormFactory;
use Formation\Form\InscriptionFrais\InscriptionFraisHydrator;
use Formation\Form\InscriptionFrais\InscriptionFraisHydratorFactory;
use Formation\Form\Justification\JustificationForm;
use Formation\Form\Justification\JustificationFormFactory;
use Formation\Form\Justification\JustificationHydrator;
use Formation\Form\Justification\JustificationHydratorFactory;
use Formation\Provider\Privilege\FormationinstanceinscritPrivileges;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Provider\Privilege\InscriptionPrivileges;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\Inscription\InscriptionServiceFactory;
use Formation\Service\InscriptionFrais\InscriptionFraisService;
use Formation\Service\InscriptionFrais\InscriptionFraisServiceFactory;
use Formation\View\Helper\InscriptionsViewHelper;
use Formation\View\Helper\InscriptionViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Inscription' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            InscriptionPrivileges::INSCRIPTION_AFFICHER,
                        ],
                        'resources' => ['Inscription'],
                        'assertion' => InscriptionAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_INDEX,
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'afficher'
                    ],
                    'privileges' => [
                        InscriptionPrivileges::INSCRIPTION_AFFICHER,
                    ],
                    'assertion' => InscriptionAssertion::class,
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'ajouter',
                        'modifier',
                        'historiser',
                        'restaurer',
                        'supprimer',
                        'renseigner-frais',

                        'envoyer-convocation',
                        'envoyer-attestation',
                        'envoyer-absence',
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
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'inscription',
                        'desinscription',
                    ],
                    'roles' => [
                        'Agent',
                        'Stagiaire externe',
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'telecharger-attestation',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'televerser-attestation',
                        'supprimer-attestation',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => InscriptionController::class,
                    'action' => [
                        'repondre-enquete',
                        'valider-enquete'
                    ],
                    'privileges' => [
                        InscriptionPrivileges::INSCRIPTION_ENQUETE,
                    ],
                    //todo creer assertion ...
                    //'assertion' => InscriptionAssertion::class,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'formation' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/formation',
                    'defaults' => [
                        'controller' => FormationController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'inscription' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/inscription',
                            'defaults' => [
                                'controller' => InscriptionController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter[/:session]',
                                    'defaults' => [
                                        /** @see InscriptionController::afficherAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::modifierAction() */
                                        'action' => 'modifier',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::supprimerAction() */
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            /** Attestation et convocation ************************************************************/
                            'envoyer-convocation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/envoyer-convocation/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerConvocationAction() */
                                        'action' => 'envoyer-convocation',
                                    ],
                                ],
                            ],
                            'envoyer-attestation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/envoyer-attestation/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerAttestationAction() */
                                        'action' => 'envoyer-attestation',
                                    ],
                                ],
                            ],
                            'envoyer-absence' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/envoyer-absence/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerAbsenceAction() */
                                        'action' => 'envoyer-absence',
                                    ],
                                ],
                            ],
                            'televerser-attestation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/televerser-attestation/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::televerserAttestationAction() */
                                        'action' => 'televerser-attestation',
                                    ],
                                ],
                            ],
                            'telecharger-attestation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/telecharger-attestation/:inscription/:attestation',
                                    'defaults' => [
                                        /** @see InscriptionController::telechargerAttestationAction() */
                                        'action' => 'telecharger-attestation',
                                    ],
                                ],
                            ],
                            'supprimer-attestation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer-attestation/:inscription/:attestation',
                                    'defaults' => [
                                        /** @see InscriptionController::supprimerAttestationAction() */
                                        'action' => 'supprimer-attestation',
                                    ],
                                ],
                            ],
                            /** Validation ****************************************************************************/
                            'valider-responsable' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/valider-responsable/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::validerResponsableAction() */
                                        'action' => 'valider-responsable',
                                    ],
                                ],
                            ],
                            'refuser-responsable' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/refuser-responsable/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::refuserResponsablehAction() */
                                        'action' => 'refuser-responsable',
                                    ],
                                ],
                            ],
                            'valider-drh' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/valider-drh/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::validerDrhAction() */
                                        'action' => 'valider-drh',
                                    ],
                                ],
                            ],
                            'refuser-drh' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/refuser-drh/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::refuserDrhAction() */
                                        'action' => 'refuser-drh',
                                    ],
                                ],
                            ],
                            /** Classement ****************************************************************************/
                            'envoyer-liste-principale' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/envoyer-liste-principale/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerListePrincipaleAction() */
                                        'action' => 'envoyer-liste-principale',
                                    ],
                                ],
                            ],
                            'envoyer-liste-complementaire' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/envoyer-liste-complementaire/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::envoyerListeComplementaireAction() */
                                        'action' => 'envoyer-liste-complementaire',
                                    ],
                                ],
                            ],
                            'retirer-liste' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/retirer-liste/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::retirerListeAction() */
                                        'action' => 'retirer-liste',
                                    ],
                                ],
                            ],
                            'classer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/classer/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::classerAction() */
                                        'action' => 'classer',
                                    ],
                                ],
                            ],
                            /** Autre * */
                            'renseigner-frais' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/renseigner-frais/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::renseignerFraisAction() */
                                        'controller' => InscriptionController::class,
                                        'action' => 'renseigner-frais',
                                    ],
                                ],
                            ],
                            /** Partie Agent **/
                            'creer-inscription' => [
                                'type' => Segment::class,
                                'options' => [
                                    /** @see InscriptionController::inscriptionAction() */
                                    'route' => '/creer-inscription/:session/:agent',
                                    'defaults' => [
                                        'action' => 'inscription',
                                    ],
                                ],
                            ],
                            'annuler-inscription' => [
                                'type' => Segment::class,
                                'options' => [
                                    /** @see InscriptionController::desinscriptionAction() */
                                    'route' => '/annuler-inscription/:inscription',
                                    'defaults' => [
                                        'action' => 'desinscription',
                                    ],
                                ],
                            ],
                            /** ENQUETE ********************************************************************************/
                            'repondre-enquete' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/repondre-enquete/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::repondreEnqueteAction() */
                                        'action' => 'repondre-enquete',
                                    ],
                                ],
                            ],
                            'valider-enquete' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/valider-enquete/:inscription',
                                    'defaults' => [
                                        /** @see InscriptionController::validerEnqueteAction() */
                                        'action' => 'valider-enquete',
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
            InscriptionService::class => InscriptionServiceFactory::class,
            InscriptionFraisService::class => InscriptionFraisServiceFactory::class,
            InscriptionAssertion::class => InscriptionAssertionFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            InscriptionController::class => InscriptionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            InscriptionForm::class => InscriptionFormFactory::class,
            InscriptionFraisForm::class => InscriptionFraisFormFactory::class,
            JustificationForm::class => JustificationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            InscriptionHydrator::class => InscriptionHydratorFactory::class,
            JustificationHydrator::class => JustificationHydratorFactory::class,
            InscriptionFraisHydrator::class => InscriptionFraisHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'inscription' => InscriptionViewHelper::class,
            'inscriptions' => InscriptionsViewHelper::class,
        ],
    ],

];