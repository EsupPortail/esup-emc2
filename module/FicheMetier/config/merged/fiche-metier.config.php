<?php

namespace FichePoste;

use FicheMetier\Controller\FicheMetierController;
use FicheMetier\Controller\FicheMetierControllerFactory;
use FicheMetier\Form\CodeEmploiType\CodeEmploiTypeForm;
use FicheMetier\Form\CodeEmploiType\CodeEmploiTypeFormFactory;
use FicheMetier\Form\CodeEmploiType\CodeEmploiTypeHydrator;
use FicheMetier\Form\CodeEmploiType\CodeEmploiTypeHydratorFactory;
use FicheMetier\Form\FicheMetierIdentification\FicheMetierIdentificationForm;
use FicheMetier\Form\FicheMetierIdentification\FicheMetierIdentificationFormFactory;
use FicheMetier\Form\FicheMetierIdentification\FicheMetierIdentificationHydrator;
use FicheMetier\Form\FicheMetierIdentification\FicheMetierIdentificationHydratorFactory;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormFactory;
use FicheMetier\Form\FicheMetierImportation\FichierMetierImportationHydrator;
use FicheMetier\Form\FicheMetierImportation\FichierMetierImportationHydratorFactory;
use FicheMetier\Form\Raison\RaisonForm;
use FicheMetier\Form\Raison\RaisonFormFactory;
use FicheMetier\Form\Raison\RaisonHydrator;
use FicheMetier\Form\Raison\RaisonHydratorFactory;
use FicheMetier\Form\SelectionFicheMetier\SelectionFicheMetierForm;
use FicheMetier\Form\SelectionFicheMetier\SelectionFicheMetierFormFactory;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\FicheMetier\FicheMetierServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_INDEX,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'afficher',
                        'refresh-activites',
                        'refresh-applications',
                        'refresh-competences',
                        'refresh-missions',
                        'refresh-raison',
                        'exporter',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AFFICHER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'ajouter',
                        'dupliquer',
                        'lister-agents',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_AJOUTER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_HISTORISER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_DETRUIRE,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'modifier',
                        'modifier-identification',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'modifier-etat',
                        'modifier-raison',

                        'modifier-famille-professionnelle',
                        'supprimer-famille-professionnelle',
                        'modifier-niveau-carriere',
                        'supprimer-niveau-carriere',
                        'modifier-code-fonction',
                        'supprimer-code-fonction',
                        'modifier-code-emploi-type',
                        'supprimer-code-emploi-type',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'gerer-missions-principales',
                        'retirer-mission',
                        'bouger-mission',

                        'gerer-activites',
                        'retirer-activite',
                        'bouger-activite',

                        'gerer-competences',
                        'gerer-applications',
                        'gerer-competences-specifiques',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'fiche-metier' => [
                                'label' => 'Fiches métiers',
                                'route' => 'fiche-metier',
                                'resource' => FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX),
                                'order' => 2045,
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
            'fiche-metier' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/fiche-metier',
                    'defaults' => [
                        /** @see FicheMetierController::indexAction() */
                        'controller' => FicheMetierController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/afficher/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::afficherAction() */
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'lister-agents' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/lister-agents/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::listerAgentsAction() */
                                'action' => 'lister-agents',
                            ],
                        ],
                    ],
                    'exporter' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/exporter/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::exporterAction() */
                                'action' => 'exporter',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                /** @see FicheMetierController::ajouterAction() */
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'modifier-identification' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-identification/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierIdentificationAction() */
                                'action' => 'modifier-identification',
                            ],
                        ],
                    ],
                    'dupliquer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dupliquer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::dupliquerAction() */
                                'action' => 'dupliquer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierAction() */
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/historiser/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::historiserAction() */
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/restaurer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::restaurerAction() */
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerAction() */
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-etat' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-etat/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierEtatAction() */
                                'action' => 'modifier-etat',
                            ],
                        ],
                    ],
                    'modifier-raison' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-raison/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierRaisonAction() */
                                'action' => 'modifier-raison',
                            ],
                        ],
                    ],
                    'modifier-code-fonction' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-code-fonction/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierCodeFonctionAction() */
                                'action' => 'modifier-code-fonction',
                            ],
                        ],
                    ],
                    'supprimer-code-fonction' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer-code-fonction/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerCodeFonctionAction() */
                                'action' => 'supprimer-code-fonction',
                            ],
                        ],
                    ],
                    'modifier-code-emploi-type' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-code-emploi-type/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierCodeEmploiTypeAction() */
                                'action' => 'modifier-code-emploi-type',
                            ],
                        ],
                    ],
                    'supprimer-code-emploi-type' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer-code-emploi-type/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerCodeEmploiTypeAction() */
                                'action' => 'supprimer-code-emploi-type',
                            ],
                        ],
                    ],
                    'modifier-famille-professionnelle' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-famille-professionnelle/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierFamilleProfessionnelleAction() */
                                'action' => 'modifier-famille-professionnelle',
                            ],
                        ],
                    ],
                    'supprimer-famille-professionnelle' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer-famille-professionnelle/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerFamilleProfessionnelleAction() */
                                'action' => 'supprimer-famille-professionnelle',
                            ],
                        ],
                    ],
                    'modifier-niveau-carriere' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/modifier-niveau-carriere/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierNiveauCarriereAction() */
                                'action' => 'modifier-niveau-carriere',
                            ],
                        ],
                    ],
                    'supprimer-niveau-carriere' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/supprimer-niveau-carriere/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerNiveauCarriereAction() */
                                'action' => 'supprimer-niveau-carriere',
                            ],
                        ],
                    ],
                    'gerer-activites' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/gerer-activites/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererActivitesAction() */
                                'action' => 'gerer-activites',
                            ],
                        ],
                    ],
                    'bouger-activite' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/bouger-activite/:fiche-metier/:activite-element/:direction',
                            'defaults' => [
                                /** @see FicheMetierController::bougerActiviteAction() */
                                'action' => 'bouger-activite'
                            ],
                        ],
                    ],
                    'retirer-activite' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/retirer-activite/:fiche-metier/:activite-element',
                            'defaults' => [
                                /** @see FicheMetierController::retirerActiviteAction() */
                                'action' => 'retirer-activite'
                            ],
                        ],
                    ],
                    'gerer-applications' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/gerer-application/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererApplicationsAction() */
                                'action' => 'gerer-applications',
                            ],
                        ],
                    ],
                    'gerer-competences' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/gerer-competences/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererCompetencesAction() */
                                'action' => 'gerer-competences',
                            ],
                        ],
                    ],
                    'gerer-competences-specifiques' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/gerer-competences-specifiques/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererCompetencesSpecifiquesAction() */
                                'action' => 'gerer-competences-specifiques',
                            ],
                        ],
                    ],
                    'gerer-missions-principales' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/gerer-missions-principales/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererMissionsPrincipalesAction() */
                                'action' => 'gerer-missions-principales',
                            ],
                        ],
                    ],
                    'bouger-mission' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/bouger-mission/:fiche-metier/:mission-element/:direction',
                            'defaults' => [
                                /** @see FicheMetierController::bougerMissionAction() */
                                'action' => 'bouger-mission'
                            ],
                        ],
                    ],
                    'retirer-mission' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/retirer-mission/:fiche-metier/:mission-element',
                            'defaults' => [
                                /** @see FicheMetierController::retirerMissionAction() */
                                'action' => 'retirer-mission'
                            ],
                        ],
                    ],
                    'refresh-activites' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/refresh-activites/:fiche-metier/:mode',
                            'defaults' => [
                                /** @see FicheMetierController::refreshActivitesAction() */
                                'action' => 'refresh-activites',
                            ],
                        ],
                    ],
                    'refresh-applications' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/refresh-applications/:fiche-metier/:mode',
                            'defaults' => [
                                /** @see FicheMetierController::refreshApplicationsAction() */
                                'action' => 'refresh-applications',
                            ],
                        ],
                    ],
                    'refresh-competences' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/refresh-competences/:fiche-metier/:mode[/:type]',
                            'defaults' => [
                                /** @see FicheMetierController::refreshCompetencesAction() */
                                'action' => 'refresh-competences',
                            ],
                        ],
                    ],
                    'refresh-missions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/refresh-missions/:fiche-metier/:mode',
                            'defaults' => [
                                /** @see FicheMetierController::refreshMissionsAction() */
                                'action' => 'refresh-missions',
                            ],
                        ],
                    ],
                    'refresh-raison' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/refresh-raison/:fiche-metier/:mode',
                            'defaults' => [
                                /** @see FicheMetierController::refreshRaisonAction() */
                                'action' => 'refresh-raison',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FicheMetierService::class => FicheMetierServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CodeEmploiTypeForm::class => CodeEmploiTypeFormFactory::class,
            FicheMetierIdentificationForm::class => FicheMetierIdentificationFormFactory::class,
            RaisonForm::class => RaisonFormFactory::class,
            SelectionFicheMetierForm::class => SelectionFicheMetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CodeEmploiTypeHydrator::class => CodeEmploiTypeHydratorFactory::class,
            FicheMetierIdentificationHydrator::class => FicheMetierIdentificationHydratorFactory::class,
            RaisonHydrator::class => RaisonHydratorFactory::class,
        ],
    ]

];