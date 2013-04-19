<?php
/**
 * UnicaenApp Local Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, 
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * Connexions aux annuaires LDAP.
     * NB: Compte admin requis pour récupération coordonnées, affectations, rôles, etc.
     */
    'ldap' => array(
        'connection' => array(
            'default' => array(
                'params' => array(
                    'host'                => 'ldap.unicaen.fr',
                    'username'            => "uid=applidev,ou=system,dc=unicaen,dc=fr",
                    'password'            => "Ifq1pdeS2of_7DC",
                    'baseDn'              => "ou=people,dc=unicaen,dc=fr",
                    'bindRequiresDn'      => true,
                    'accountFilterFormat' => "(&(objectClass=posixAccount)(supannAliasLogin=%s))",
                )
            )
        )
    ),
    /**
     * Connexions aux bases de données via Doctrine (http://www.doctrine-project.org/).
     */
//    'doctrine' => array(
//        'connection' => array(
//            'orm_default' => array(
//                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
//                'params' => array(
//                    'host'     => 'localhost',
//                    'port'     => '3306',
//                    'user'     => 'root',
//                    'password' => 'root',
//                    'dbname'   => 'squelette',
//                )
//            ),
//        ),
//    ),
    /**
     * Options concernant l'envoi de mail
     */
    'mail' => array(
        'transport_options' => array(
            'host' => 'smtp.unicaen.fr',
            'port' => 25,
        ),
        'redirect_to' => array('dsi.applications@unicaen.fr'),
        'do_not_send' => false,
    ),
);

/**
 * You do not need to edit below this line
 */
return array(
    'unicaen-app' => $settings,
);