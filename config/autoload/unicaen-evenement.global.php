<?php

use Application\Event\RgpdRenderer\RgpdRendererEvenement;
use Application\Provider\EvenementProvider;
use Application\Provider\EvenementProvider as ApplicationEvenementProvider;
use Application\Service\Evenement\SynchroOctopusService;
use EntretienProfessionnel\Provider\Event\EvenementProvider as EntretienProfessionnelEvenementProvider;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationService;
use Formation\Event\Convocation\ConvocationEvent;
use Formation\Event\InscriptionCloture\InscriptionClotureEvent;
use Formation\Provider\Event\EvenementProvider as FormationEvenementProvider;
use Formation\Service\Evenement\NotificationFormationsOuvertesService;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;

return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,

            ApplicationEvenementProvider::SYNCHRO_OCTOPUS                                               => SynchroOctopusService::class,
            EvenementProvider::RGPD_UNICAEN_RENDERER                                                    => RgpdRendererEvenement::class,

            FormationEvenementProvider::NOTIFICATION_FORMATION_OUVERTE                                  => NotificationFormationsOuvertesService::class,
            FormationEvenementProvider::RAPPEL_FORMATION_AGENT_AVANT                                    => RappelAgentAvantFormationService::class,
            FormationEvenementProvider::INSCRIPTION_CLOTURE                                             => InscriptionClotureEvent::class,
            FormationEvenementProvider::CONVOCATION                                                     => ConvocationEvent::class,

            EntretienProfessionnelEvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT                         => RappelCampagneAvancementService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL                     => RappelEntretienProfessionnelService::class,
            EntretienProfessionnelEvenementProvider::RAPPEL_PAS_OBSERVATION_ENTRETIEN_PROFESSIONNEL     => RappelPasObservationService::class,

        ],

        'icone' => [
            EntretienProfessionnelEvenementProvider::RAPPEL_ENTRETIEN_PROFESSIONNEL    => 'icon rappel',
        ],
    ],
];