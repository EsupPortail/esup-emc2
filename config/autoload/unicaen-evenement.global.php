<?php

use Application\Service\Evenement\SynchroOctopusService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use Formation\Service\Evenement\NotificationFormationsOuvertesService;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;

return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,

            // Évènements Application
            Type::NOTIFICATION_FORMATION_OUVERTE     => NotificationFormationsOuvertesService::class,
            Type::RAPPEL_ENTRETIEN_PROFESSIONNEL     => RappelEntretienProfessionnelService::class,
            Type::RAPPEL_FORMATION_AGENT_AVANT       => RappelAgentAvantFormationService::class,
            Type::SYNCHRO_OCTOPUS                    => SynchroOctopusService::class,
        ],

        'icone' => [
            Type::RAPPEL_ENTRETIEN_PROFESSIONNEL    => 'icon rappel',
        ],
    ],
];