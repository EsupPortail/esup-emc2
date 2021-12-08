<?php


use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;

return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,

            // Évènements Application
            Type::RAPPEL_ENTRETIEN_PROFESSIONNEL     => RappelEntretienProfessionnelService::class,
            //Type::SYNCHRO_OCTOPUS                    => EvenementApplication\Compte\EvenementCompteService::class,
        ],

        'icone' => [
            Type::RAPPEL_ENTRETIEN_PROFESSIONNEL    => 'icon rappel',
        ],
    ],
];