<?php

namespace Application;

use Application\Provider\Privilege\ActivitePrivileges;
use Application\Provider\Privilege\AdministrationPrivileges;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Provider\Privilege\PersoPrivileges;
use Application\Provider\Privilege\PostePrivileges;
use Application\Provider\Privilege\RessourceRhPrivileges;

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
                        'resource' =>  PersoPrivileges::getResourceId(PersoPrivileges::MENU),
                        'pages' => [
                            'index-personnel' => [
                                'visible' => true,
                                'order' => 100,
                                'label' => 'Mon accueil',
                                'icon' => 'fas fa-angle-right',
                                'route' => 'index-personnel',
                                'resource' =>  PersoPrivileges::getResourceId(PersoPrivileges::MENU),
                            ],
                            'entretien' => [
                                'visible' => true,
                                'order' => 200,
                                'label' => 'Mes entretiens Pro.',
                                'icon' => 'fas fa-angle-right',
                                'route' => 'agent/entretien-professionnel',
                                'resource' =>  PersoPrivileges::getResourceId(PersoPrivileges::ENTRETIEN),
                            ],
                            'fichier' => [
                                'visible' => true,
                                'order' => 300,
                                'label' => 'Mes fichiers',
                                'icon' => 'fas fa-angle-right',
                                'route' => 'agent/fichiers',
                                'resource' =>  PersoPrivileges::getResourceId(PersoPrivileges::FICHIER),
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
                        'label' => 'Ressources',
                        'route' => 'ressource-rh',
                        'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                        'pages' => [
                            [
                                'order' => 1,
                                'label' => 'Les agents',
                                'route' => 'agent',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 2,
                                'label' => 'Les corps',
                                'route' => 'ressource-rh/index-corps',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 2,
                                'label' => 'Les grades',
                                'route' => 'ressource-rh/index-grade',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 3,
                                'label' => 'Les correspondances',
                                'route' => 'ressource-rh/index-correspondance',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 4,
                                'label' => 'Familles, domaines et métiers',
                                'route' => 'ressource-rh/index-metier-famille-domaine',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 7,
                                'label' => 'Sites et bâtiments',
                                'route' => 'immobilier',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 8,
                                'label' => 'Les structures',
                                'route' => 'structure',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 100,
                                'label' => 'Cartographie',
                                'route' => 'ressource-rh/cartographie',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],

                        ],
                    ],
                    'entretien' => [
                        'order' => 100,
                        'label' => 'Entretiens',
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
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            'autoform' => [
                                'label'      => "Autoform",
                                'title'      => "Module Autoform",
                                'route'      => 'autoform/formulaires',
                                'roles'      => [],
//                                'withtarget' => true,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            'fichier' => [
                                'label'      => "Fichier",
                                'title'      => "Module Fichier",
                                'route'      => 'index-fichier',
                                'roles'      => [],
//                                'withtarget' => true,
                                'dropdown-header' => true,
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
                                'order' => 1,
                                'label' => 'Les missions principales',
                                'route' => 'activite',
                                'privileges' => ActivitePrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right',
                            ],
                            [
                                'order' => 2,
                                'label' => 'Les missions spécifiques',
                                'route' => 'ressource-rh/index-mission-specifique',
                                'resource' =>  RessourceRhPrivileges::getResourceId(RessourceRhPrivileges::AFFICHER) ,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 3,
                                'label' => 'Les applications',
                                'route' => 'application',
                                'privileges' => ApplicationPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
//                            [
//                                'order' => 4,
//                                'label' => 'Les formations',
//                                'route' => 'application',
//                                'privileges' => ApplicationPrivileges::AFFICHER,
//                                'dropdown-header' => true,
//                                'icon' => 'fas fa-angle-right'
//                            ],
                            [
                                'order' => 7,
                                'label' => 'Les fiches de poste',
                                'route' => 'fiche-poste',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 6,
                                'label' => 'Les fiches métiers',
                                'route' => 'fiche-metier-type',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'order' => 5,
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