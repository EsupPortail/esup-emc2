<?php

use UnicaenEvenement\Controller\IndexController;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\EvenementCollection\EvenementCollectionService;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'unicaen-evenement' => [
        'service' => [
            // Évènements de base
            Type::COLLECTION => EvenementCollectionService::class,
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