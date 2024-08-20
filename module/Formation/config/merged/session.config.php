<?php

namespace Formation;

use Formation\Assertion\SessionAssertion;
use Formation\Assertion\SessionAssertionFactory;
use Formation\Controller\SessionController;
use Formation\Controller\SessionControllerFactory;
use Formation\Controller\InscriptionController;
use Formation\Form\Session\SessionForm;
use Formation\Form\Session\SessionFormFactory;
use Formation\Form\Session\SessionHydrator;
use Formation\Form\Session\SessionHydratorFactory;
use Formation\Provider\Privilege\FormationinstancePrivileges;
use Formation\Service\Session\SessionService;
use Formation\Service\Session\SessionServiceFactory;
use Formation\View\Helper\FormationInstanceArrayViewHelper;
use Formation\View\Helper\SessionInformationsViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Unicaen\Console\Router\Simple;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Session' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER,
                        ],
                        'resources' => ['Session'],
                        'assertion' => SessionAssertion::class
                    ],
                ],
            ]
        ],

        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_INDEX
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER,
                    ],
                    'assertion' => SessionAssertion::class,
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'rechercher',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'ajouter',
                        'ajouter-avec-formulaire',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AJOUTER,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'modifier-informations',
                        'selectionner-gestionnaires',
                        'export-emargement',
                        'export-tous-emargements',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_MODIFIER,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'ouvrir-inscription',
                        'fermer-inscription',
                        'envoyer-convocation',
                        'demander-retour',
                        'cloturer',
                        'changer-etat',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'exporter-inscription',
                        'resultat-enquete',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_AFFICHER_INSCRIPTION,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'annuler',
                        'reouvrir',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_ANNULER,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'restaurer',
                        'historiser',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_HISTORISER,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_SUPPRIMER,
                    ],
                ],
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'ajouter-formateur',
                        'retirer-formateur',
                    ],
                    'privileges' => [
                        FormationinstancePrivileges::FORMATIONINSTANCE_GERER_FORMATEUR,
                    ],
                ],
                //console
                [
                    'controller' => SessionController::class,
                    'action' => [
                        'formation-console',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

    'navigation' => [
        'formation' => [
            'home' => [
                'pages' => [
                    'gestion-formation' => [
                        'pages' => [
                            'session_' => [
                                'label' => 'Sessions',
                                'route' => 'formation/session',
                                'resource' => PrivilegeController::getResourceId(SessionController::class, 'index'),
                                'order' => 230,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'isncription_' => [
                                'label' => 'Inscriptions',
                                'route' => 'formation/inscription',
                                'resource' => PrivilegeController::getResourceId(InscriptionController::class, 'index'),
                                'order' => 240,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
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
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'session' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/session',
                            'defaults' => [
                                /** @see SessionController::indexAction() */
                                'controller' => SessionController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            /** crud ******************************************************************/
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:session',
                                    'defaults' => [
                                        /** @see SessionController::afficherAction() */
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter/:formation',
                                    'defaults' => [
                                        /** @see SessionController::ajouterAction() */
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'ajouter-avec-formulaire' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/ajouter-avec-formulaire',
                                    'defaults' => [
                                        /** @see SessionController::ajouterAvecFormulaireAction() */
                                        'action' => 'ajouter-avec-formulaire',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:session',
                                    'defaults' => [
                                        /** @see SessionController::historiserAction() */
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:session',
                                    'defaults' => [
                                        /** @see SessionController::restaurerAction() */
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:session',
                                    'defaults' => [
                                        /** @see SessionController::supprimerAction() */
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'modifier-informations' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/modifier-informations/:session',
                                    'defaults' => [
                                        /** @see SessionController::modifierInformationsAction() */
                                        'action' => 'modifier-informations',
                                    ],
                                ],
                            ],
                            /** changement d'etat *****************************************************/
                            'changer-etat' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/changer-etat/:session',
                                    'defaults' => [
                                        /** @see SessionController::changerEtatAction() */
                                        'action' => 'changer-etat',
                                    ],
                                ],
                            ],
                            'annuler' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/annuler/:session',
                                    'defaults' => [
                                        /** @see SessionController::annulerAction() */
                                        'action' => 'annuler',
                                    ],
                                ],
                            ],
                            'reouvrir' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/reouvrir/:session',
                                    'defaults' => [
                                        /** @see SessionController::reouvrirAction() */
                                        'action' => 'reouvrir',
                                    ],
                                ],
                            ],
                            'ouvrir-inscription' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ouvrir-inscription/:session',
                                    'defaults' => [
                                        /** @see SessionController::ouvrirInscriptionAction() */
                                        'action' => 'ouvrir-inscription',
                                    ],
                                ],
                            ],
                            'fermer-inscription' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/fermer-inscription/:session',
                                    'defaults' => [
                                        /** @see SessionController::fermerInscriptionAction() */
                                        'action' => 'fermer-inscription',
                                    ],
                                ],
                            ],
                            'envoyer-convocation' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/envoyer-convocation/:session',
                                    'defaults' => [
                                        /** @see SessionController::envoyerConvocationAction() */
                                        'action' => 'envoyer-convocation',
                                    ],
                                ],
                            ],
                            'demander-retour' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/demander-retour/:session',
                                    'defaults' => [
                                        /** @see SessionController::demanderRetourAction() */
                                        'action' => 'demander-retour',
                                    ],
                                ],
                            ],
                            'cloturer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/cloturer/:session',
                                    'defaults' => [
                                        /** @see SessionController::cloturerAction() */
                                        'action' => 'cloturer',
                                    ],
                                ],
                            ],
                            /** gestion des formateurs ************************************************/
                            'ajouter-formateur' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter-formateur/:session',
                                    'defaults' => [
                                        /** @see SessionController::ajouterFormateurAction() */
                                        'action' => 'ajouter-formateur',
                                    ],
                                ],
                            ],
                            'retirer-formateur' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/retirer-formateur/:session/:formateur',
                                    'defaults' => [
                                        /** @see SessionController::retirerFormateurAction() */
                                        'action' => 'retirer-formateur',
                                    ],
                                ],
                            ],
                            'selectionner-gestionnaires' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/selectionner-gestionnaires/:session',
                                    'defaults' => [
                                        /** @see SessionController::selectionnerGestionnairesAction() */
                                        'action' => 'selectionner-gestionnaires',
                                    ],
                                ],
                            ],
                            /** Enquete ***************************************************************/
                            'resultat-enquete' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/resultat-enquete/:session',
                                    'defaults' => [
                                        /** @see SessionController::resultatEnqueteAction() */
                                        'action' => 'resultat-enquete',
                                    ],
                                ],
                            ],
                            /** Fonctions utilitaires *************************************************/
                            'exporter-inscription' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/exporter-inscription/:session',
                                    'defaults' => [
                                        /** @see SessionController::exporterInscriptionAction() */
                                        'action' => 'exporter-inscription',
                                    ],
                                ],
                            ],
                            'rechercher' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/rechercher',
                                    'defaults' => [
                                        /** @see SessionController::rechercherAction() */
                                        'action' => 'rechercher',
                                    ],
                                ],
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
                            'controller' => SessionController::class,
                            'action' => 'formation-console'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            SessionService::class => SessionServiceFactory::class,
            SessionAssertion::class => SessionAssertionFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            SessionController::class => SessionControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            SessionForm::class => SessionFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            SessionHydrator::class => SessionHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formationInstanceArray' => FormationInstanceArrayViewHelper::class,
            'sessionInformations' => SessionInformationsViewHelper::class,
        ],
    ],

];