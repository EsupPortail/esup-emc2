<?php

use EntretienProfessionnel\Provider\Event\EvenementProvider AS EntretienProfessionnelEvenementProvider;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationService;
use UnicaenEvenement\Controller\IndexController;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,

            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_AUTORITE => RappelCampagneAvancementAutoriteService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_SUPERIEUR => RappelCampagneAvancementSuperieurService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL => RappelEntretienProfessionnelService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_PAS_OBSERVATION_ENTRETIEN_PROFESSIONNEL => RappelPasObservationService::class,
        ],

        'icone' => [
            Type::COLLECTION => 'icon icon-listing',
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [ // >>>>> adaptez la clé selon votre menu Administration, le cas échéant.
                        'pages' => [
                            'unicaen-evenement' => [
                                'label' => 'Événement',
                                'route' => 'unicaen-evenement',
                                'resource' => PrivilegeController::getResourceId(IndexController::class, 'index'),
                                'order' => 11002,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],

        /** Délai maximal avant l'annulation pour faute de trop grand retard  */
        'delai-peremption' => 'P7D',
    ],
];