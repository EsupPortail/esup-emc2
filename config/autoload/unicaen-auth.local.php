<?php
/**
 * UnicaenAuth Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * Paramètres de connexion au serveur CAS :
     * - pour désactiver l'authentification CAS, le tableau 'cas' doit être vide.
     * - pour l'activer, renseigner les paramètres.
     */
    'cas' => array(
//        'connection' => array(
//            'default' => array(
//                'params' => array(
//                    'hostname' => 'cas.unicaen.fr',
//                    'port' => 443,
//                    'version' => "2.0",
//                    'uri' => "",
//                    'debug' => false,
//                ),
//            ),
//        ),
    ),
    /**
     * Mot de passe sésame, chiffré avec l'algo Bcrypt
     * $bcrypt = new \Zend\Crypt\Password\Bcrypt(); 
     * echo $bcrypt->create('votreMotDePasseSesame');
     */
    'sesame_password' => '$2y$14$jbCVltklcys8TQj3hu30.OcKMi7rtUgmu3eo/nxMXynYvcZ5iHF8q',
);

/**
 * You do not need to edit below this line
 */
return array(
    'unicaen-auth' => $settings,
);