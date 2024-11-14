<?php

use Application\Event\RgpdRenderer\RgpdRendererEvenement;
use Application\Provider\EvenementProvider;
use Application\Provider\EvenementProvider as ApplicationEvenementProvider;
use Application\Service\Evenement\SynchroOctopusService;
use EntretienProfessionnel\Provider\Event\EvenementProvider as EntretienProfessionnelEvenementProvider;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationService;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;

return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,

            ApplicationEvenementProvider::SYNCHRO_OCTOPUS => SynchroOctopusService::class,
            EvenementProvider::RGPD_UNICAEN_RENDERER => RgpdRendererEvenement::class,


            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_AUTORITE => RappelCampagneAvancementAutoriteService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_SUPERIEUR => RappelCampagneAvancementSuperieurService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL => RappelEntretienProfessionnelService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_PAS_OBSERVATION_ENTRETIEN_PROFESSIONNEL => RappelPasObservationService::class,
        ],
    ],
];