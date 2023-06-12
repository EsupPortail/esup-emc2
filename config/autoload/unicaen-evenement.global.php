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
use Formation\Event\Convocation\ConvocationEvent;
use Formation\Event\DemandeRetour\DemandeRetourEvent;
use Formation\Event\InscriptionCloture\InscriptionClotureEvent;
use Formation\Event\SessionCloture\SessionClotureEvent;
use Formation\Provider\Event\EvenementProvider as FormationEvenementProvider;
use Formation\Service\Evenement\NotificationFormationsOuvertesService;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use Structure\Event\InfoStructure\InfoStructureEvent;
use Structure\Provider\Event\EvenementProvider as StructureEvenementProvider;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;

return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,

            ApplicationEvenementProvider::SYNCHRO_OCTOPUS => SynchroOctopusService::class,
            EvenementProvider::RGPD_UNICAEN_RENDERER => RgpdRendererEvenement::class,

            FormationEvenementProvider::NOTIFICATION_FORMATION_OUVERTE => NotificationFormationsOuvertesService::class,
            FormationEvenementProvider::RAPPEL_FORMATION_AGENT_AVANT => RappelAgentAvantFormationService::class,
            FormationEvenementProvider::INSCRIPTION_CLOTURE => InscriptionClotureEvent::class,
            FormationEvenementProvider::CONVOCATION => ConvocationEvent::class,
            FormationEvenementProvider::DEMANDE_RETOUR => DemandeRetourEvent::class,
            FormationEvenementProvider::SESSION_CLOTURE => SessionClotureEvent::class,

            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_AUTORITE => RappelCampagneAvancementAutoriteService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_SUPERIEUR => RappelCampagneAvancementSuperieurService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL => RappelEntretienProfessionnelService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_PAS_OBSERVATION_ENTRETIEN_PROFESSIONNEL => RappelPasObservationService::class,

            StructureEvenementProvider::INFO_STRUCTURE => InfoStructureEvent::class,

        ],

        'icone' => [
            EntretienProfessionnelEvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL => 'icon rappel',
        ],
    ],
];