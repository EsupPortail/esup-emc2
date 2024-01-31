<?php
/**
 * Configuration globale du module UnicaenApp.
 */

use Laminas\Session\Storage\SessionArrayStorage;

return [
    'session_config' => [
        'cookie_lifetime' => 60*60*8,
        'gc_maxlifetime'  => 60*60*24*30,
    ],

    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],

    'unicaen-app' => [

        'hostlocalization' => [
            'activated' => false,
            'proxies' => [
                //xxx.xx.xx.xxx
            ],
            'reverse-proxies' => [
                //xxx.xx.xx.xxx
            ],
            'masque-ip' => '',
        ],

        /**
         * Informations concernant cette application
         */
        'app_infos' => [
            'nom'     => "EMC2",
            'desc'    => "Emploi Mobilité Carrière Compétences",
            'version' => "4.4.2",
            'date'    => "31/01/2024",

//            'liens' => [
//                'COMUE' => [
//                    'image' => 'img/placeholder.jpeg',
//                    'url' => 'https://www.google.com/search?q=placeholder',
//                ],
//            ],
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 0, // 0 <=> aucune requête exécutée


    ],
];
