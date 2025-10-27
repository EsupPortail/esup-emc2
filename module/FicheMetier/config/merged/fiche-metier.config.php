<?php

namespace FichePoste;

use FicheMetier\Controller\FicheMetierController;
use FicheMetier\Controller\FicheMetierControllerFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Form\CodeFonction\CodeFonctionFormFactory;
use FicheMetier\Form\CodeFonction\CodeFonctionHydrator;
use FicheMetier\Form\CodeFonction\CodeFonctionHydratorFactory;
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
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionService;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

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
                        'refresh-applications',
                        'refresh-competences',
                        'refresh-missions',
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
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'modifier-etat',
                        'modifier-expertise',
                        'modifier-metier',
                        'modifier-raison',
                        'modifier-code-fonction',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'deplacer-mission',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
                [
                    'controller' => FicheMetierController::class,
                    'action' => [
                        'gerer-missions-principales',
                        'gerer-applications',
                        'gerer-competences',
                        'gerer-competences-specifiques',
                    ],
                    'privileges' => [
                        FicheMetierPrivileges::FICHEMETIER_MODIFIER,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'fiche-metier' => [
                                'label' => 'Fiches métiers',
                                'route' => 'fiche-metier',
                                'resource' =>  FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX) ,
                                'order'    => 2045,
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
            'fiche-metier' => [
                'type'  => Literal::class,
                'options' => [
                    'route'    => '/fiche-metier',
                    'defaults' => [
                        /** @see FicheMetierController::indexAction() */
                        'controller' => FicheMetierController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'afficher' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/afficher/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::afficherAction() */
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'lister-agents' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/lister-agents/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::listerAgentsAction() */
                                'action'     => 'lister-agents',
                            ],
                        ],
                    ],
                    'exporter' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/exporter/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::exporterAction() */
                                'action'     => 'exporter',
                            ],
                        ],
                    ],
                    'ajouter' => [
                        'type'  => Literal::class,
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                /** @see FicheMetierController::ajouterAction() */
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'dupliquer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/dupliquer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::dupliquerAction() */
                                'action'     => 'dupliquer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierAction() */
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/historiser/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::historiserAction() */
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/restaurer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::restaurerAction() */
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/supprimer/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::supprimerAction() */
                                'action'     => 'supprimer',
                            ],
                        ],
                    ],
                    'modifier-etat' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-etat/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierEtatAction() */
                                'action'     => 'modifier-etat',
                            ],
                        ],
                    ],
                    'modifier-expertise' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-expertise/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierExpertiseAction() */
                                'action'     => 'modifier-expertise',
                            ],
                        ],
                    ],
                    'modifier-metier' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-metier/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierMetierAction() */
                                'action'     => 'modifier-metier',
                            ],
                        ],
                    ],
                    'modifier-raison' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-raison/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierRaisonAction() */
                                'action'     => 'modifier-raison',
                            ],
                        ],
                    ],
                    'modifier-code-fonction' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/modifier-code-fonction/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::modifierCodeFonctionAction() */
                                'action'     => 'modifier-code-fonction',
                            ],
                        ],
                    ],
                    'deplacer-mission' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/deplacer-mission[/:fiche-metier/:mission-principale/:direction]',
                            'defaults' => [
                                /** @see FicheMetierController::deplacerMissionAction() */
                                'controller' => FicheMetierController::class,
                                'action'     => 'deplacer-mission',
                            ],
                        ],
                    ],
                    'gerer-applications' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-application/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererApplicationsAction() */
                                'action'     => 'gerer-applications',
                            ],
                        ],
                    ],
                    'gerer-competences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-competences/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererCompetencesAction() */
                                'action'     => 'gerer-competences',
                            ],
                        ],
                    ],
                    'gerer-competences-specifiques' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-competences-specifiques/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererCompetencesSpecifiquesAction() */
                                'action'     => 'gerer-competences-specifiques',
                            ],
                        ],
                    ],
                    'gerer-missions-principales' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/gerer-missions-principales/:fiche-metier',
                            'defaults' => [
                                /** @see FicheMetierController::gererMissionsPrincipalesAction() */
                                'action'     => 'gerer-missions-principales',
                            ],
                        ],
                    ],
                    'refresh-applications' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/refresh-applications/:fiche-metier/:mode',
                            'defaults' => [
                                /** @see FicheMetierController::refreshApplicationsAction() */
                                'action'     => 'refresh-applications',
                            ],
                        ],
                    ],
                    'refresh-competences' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/refresh-competences/:fiche-metier/:mode[/:type]',
                            'defaults' => [
                                /** @see FicheMetierController::refreshCompetencesAction() */
                                'action'     => 'refresh-competences',
                            ],
                        ],
                    ],
                    'refresh-missions' => [
                        'type'  => Segment::class,
                        'options' => [
                            'route'    => '/refresh-missions/:fiche-metier/:mode',
                            'defaults' => [
                                /** @see FicheMetierController::refreshMissionsAction() */
                                'action'     => 'refresh-missions',
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
            FicheMetierMissionService::class => FicheMetierMissionServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FicheMetierController::class => FicheMetierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CodeFonctionForm::class => CodeFonctionFormFactory::class,
            FicheMetierImportationForm::class => FicheMetierImportationFormFactory::class,
            RaisonForm::class => RaisonFormFactory::class,
            SelectionFicheMetierForm::class => SelectionFicheMetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CodeFonctionHydrator::class => CodeFonctionHydratorFactory::class,
            FichierMetierImportationHydrator::class => FichierMetierImportationHydratorFactory::class,
            RaisonHydrator::class => RaisonHydratorFactory::class,
        ],
    ]

];