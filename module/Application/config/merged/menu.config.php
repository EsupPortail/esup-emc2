<?php

namespace Application;

use Application\Provider\Privilege\ActivitePrivileges;
use Application\Provider\Privilege\AffectationPrivileges;
use Application\Provider\Privilege\ApplicationPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'fiche-metier' => [
//                        'order' => -10,
                        'label' => 'Fiches',
//                        'title' => "Fiche métier",
                        'route' => 'activite',
                        'roles' => [], //PrivilegeController::getResourceId(__NAMESPACE__ . '\Controller\Administration', 'index'),
                        'pages' => [
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
                                'label' => 'Les activités',
                                'route' => 'activite',
                                'privileges' => ActivitePrivileges::AFFICHER,
                                'dropdown-header' => true,
                                'icon' => 'fas fa-angle-right'
                            ],
                            [
                                'label' => 'Les affectations',
                                'route' => 'affectation',
                                'privileges' => AffectationPrivileges::AFFICHER,
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
                        ],
                    ],
                ],
            ],
        ],
    ],
];