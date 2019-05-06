<?php

namespace Application;

use Application\Provider\Privilege\ActivitePrivileges;
use Application\Provider\Privilege\AdministrationPrivileges;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Provider\Privilege\FonctionPrivileges;
use Application\Provider\Privilege\PostePrivileges;
use Application\Provider\Privilege\RessourceRhPrivileges;
use Application\Provider\Privilege\StructurePrivileges;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'droits' => [
                        'visible' => false,
                    ],
                    'index-personnel' => [
//                        'visible' => false,
                        'label' => 'Accueil',
                        'route' => 'index-personnel',
                        'roles' => [
                            'Personnel'
                        ],
                        'pages' => [
                            'index-personnel' => [
                                'visible' => true,
                                'order' => 100,
                                'label' => 'Mon accueil',
                                'icon' => 'fas fa-angle-right',
                                'route' => 'index-personnel',
                                'roles' => [
                                    'Personnel',
                                ],
                            ],
                            'entretien' => [
                                'visible' => true,
                                'order' => 200,
                                'label' => 'Mes entretiens Pro.',
                                'icon' => 'fas fa-angle-right',
                                'route' => 'agent/entretien-professionnel',
                                'roles' => [
                                    'Personnel',
                                ],
                            ],
                            'fichier' => [
                                'visible' => true,
                                'order' => 300,
                                'label' => 'Mes fichiers',
                                'icon' => 'fas fa-angle-right',
                                'route' => 'agent/fichiers',
                                'roles' => [
                                    'Personnel',
                                ],
                            ],
//                            'rgpd' => [
//                                'visible' => true,
//                                'order' => 400,
//                                'label' => 'Mes données',
//                                'icon' => 'fas fa-angle-right',
//                                'route' => 'index-personnel',
//                                'roles' => [
//                                    'Personnel',
//                                ],
//                            ],

                        ],
                    ],
                    'ressource' => [
                        'order' => 100,
                        'label' => 'Ressources RH',
                        'route' => 'ressource-rh',
                        'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                        'pages' => [
                            [
                                'order' => 1,
                                'label' => 'Les corps, grades et status',
                                'route' => 'ressource-rh/index-corps-grade-status',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 2,
                                'label' => 'Les correspondances',
                                'route' => 'ressource-rh/index-correspondance',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 3,
                                'label' => 'Les domaines',
                                'route' => 'ressource-rh/index-domaine',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 4,
                                'label' => 'Les fonctions',
                                'route' => 'fonction',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 5,
                                'label' => 'Les métiers et familles de métiers',
                                'route' => 'ressource-rh/index-metier-et-famille',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 6,
                                'label' => 'Les structures',
                                'route' => 'structure',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],


                        ],
                    ],
                    'entretien' => [
                        'order' => 100,
                        'label' => 'Entretiens Pro.',
                        'route' => 'entretien-professionnel',
                        'resource' => AdministrationPrivileges::getResourceId(AdministrationPrivileges::AFFICHER),
                        'pages' => [
                        ],
                    ],
                    'administration-preecog' => [
                        'order' => 1000,
                        'label' => 'Administration',
                        'title' => "Administration",
                        'route' => 'administration',
                        'resource' =>  AdministrationPrivileges::getResourceId(AdministrationPrivileges::AFFICHER) ,
                        'pages' => [
                            [
                                'label' => 'Mailing',
                                'route' => 'mailing',
                                'roles' => [], //'privileges' => MailingPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Utilisateurs',
                                'route' => 'utilisateur',
                                'roles' => [],// 'privileges' => UtilisateurPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right',
                            ],
                            'privileges' => [
                                'label'      => "Privilèges",
                                'title'      => "Gestion des privilèges",
                                'route'      => 'privilege',
                                'resource'   => \UnicaenAuth\Guard\PrivilegeController::getResourceId('UnicaenAuth\Controller\Droits', 'privileges'),
                                'withtarget' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            'autoform' => [
                                'label'      => "Autoform",
                                'title'      => "Module Autoform",
                                'route'      => 'autoform/formulaires',
                                'roles'      => [],
//                                'withtarget' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            'fichier' => [
                                'label'      => "Fichier",
                                'title'      => "Module Fichier",
                                'route'      => 'index-fichier',
                                'roles'      => [],
//                                'withtarget' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                        ],
                    ],
                    'fiche-metier' => [
//                        'order' => -10,
                        'label' => 'Fiches',
//                        'title' => "Fiche métier",
                        'route' => 'activite',
                        'resource' => FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::AFFICHER),
                        'pages' => [
                            [
                                'label' => 'Les activités',
                                'route' => 'activite',
                                'privileges' => ActivitePrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les agents',
                                'route' => 'agent',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les applications',
                                'route' => 'application',
                                'privileges' => ApplicationPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les fiches de poste',
                                'route' => 'fiche-poste',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les fiches métiers',
                                'route' => 'fiche-metier-type',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les postes',
                                'route' => 'poste',
                                'privileges' => PostePrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];