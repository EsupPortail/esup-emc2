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
            'version' => "3.1.0",
            'date'    => "05/12/2022",
            'contact' => ['mail' => "assistance-emc2@unicaen.fr", /*'tel' => "01 02 03 04 05"*/],
            'mentionsLegales'        => "http://www.unicaen.fr/acces-direct/mentions-legales/",
            'informatiqueEtLibertes' => "http://www.unicaen.fr/acces-direct/informatique-et-libertes/",

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
