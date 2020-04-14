<?php

namespace Application;

use Application\Provider\Privilege\AdministrationPrivileges;
use Application\Provider\Privilege\EntretienproPrivileges;
use Application\Provider\Privilege\FicheMetierPrivileges;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Provider\Privilege\MissionspecifiquePrivileges;

return [

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'order' => 1000,
                        'label' => 'Administration',
                        'title' => "Administration",
                        'route' => 'administration',
                        'resource' =>  AdministrationPrivileges::getResourceId(AdministrationPrivileges::AFFICHER) ,
                    ],
                    'fiche' => [
                        'order' => 500,
                        'label' => 'Fiches',
                        'title' => "Gestion des fiches, entretiens et des affectations",
                        'route' => 'fiche-poste',
                        'privileges' => [
                            EntretienproPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_INDEX),
                            FichePostePrivileges::getResourceId(FichePostePrivileges::FICHEPOSTE_INDEX),
                            FicheMetierPrivileges::getResourceId(FicheMetierPrivileges::FICHEMETIER_INDEX),
                            MissionspecifiquePrivileges::getResourceId(MissionspecifiquePrivileges::MISSIONSPECIFIQUE_AFFECTATION_INDEX),
                        ],
                    ],
                ],
            ],
        ],
    ],

];