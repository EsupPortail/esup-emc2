<?php

namespace Application;

use Application\Provider\Privilege\ActivitePrivileges;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Provider\Privilege\PostePrivileges;
use Application\Provider\Privilege\StructurePrivileges;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'droits' => [
                        'visible' => false,
                    ],
                    'ressource' => [
                        'order' => 100,
                        'label' => 'Ressources RH',
                        'route' => 'ressource-rh',
                        'roles' => [],
                        'pages' => [
                        ],
                    ],
                    'entretien' => [
                        'order' => 100,
                        'label' => 'Entretiens Pro.',
                        'route' => 'entretien-professionnel',
                        'roles' => [],
                        'pages' => [
                        ],
                    ],
                    'administration-preecog' => [
                        'order' => 1000,
                        'label' => 'Administration',
                        'title' => "Administration",
                        'route' => 'administration',
                        'roles' => [],
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
                            'roles'      => [
                                'label'      => "Rôles",
                                'title'      => "Gestion des rôles",
                                'route'      => 'droits/roles',
                                'resource'   => \UnicaenAuth\Guard\PrivilegeController::getResourceId('UnicaenAuth\Controller\Droits', 'roles'),
                                'withtarget' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            'privileges' => [
                                'label'      => "Privilèges",
                                'title'      => "Gestion des privilèges",
                                'route'      => 'droits/privileges',
                                'resource'   => \UnicaenAuth\Guard\PrivilegeController::getResourceId('UnicaenAuth\Controller\Droits', 'privileges'),
                                'withtarget' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            'autoform' => [
                                'label'      => "Autoform",
                                'title'      => "Module Autoform",
                                'route'      => 'autoform',
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
                        'roles' => [], //PrivilegeController::getResourceId(__NAMESPACE__ . '\Controller\Administration', 'index'),
                        'pages' => [
                            [
                                'label' => 'Les activités',
                                'route' => 'activite',
                                'privileges' => ActivitePrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les structures',
                                'route' => 'structure',
                                'privileges' => StructurePrivileges::AFFICHER,
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
                                'label' => 'Les fiches métiers',
                                'route' => 'fiche-metier',
                                'privileges' => FicheMetierPrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les fiches types',
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