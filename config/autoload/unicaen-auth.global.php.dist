<?php
/**
 * UnicaenAuth Global Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    'bjyauthorize' => array(
        // default role for unauthenticated users
        'default_role' => false,
    ),
    /**
     * Mot de passe sésame, chiffré avec l'algo Bcrypt
     * $bcrypt = new \Zend\Crypt\Password\Bcrypt(); 
     * echo $bcrypt->create('votreMotDePasseSesame');
     */
    'sesame_password' => false,
    /**
     * Flag indiquant si l'utilisateur authenitifié avec succès via l'annuaire LDAP doit
     * être enregistré/mis à jour dans la table des utilisateurs de l'appli.
     */
    'save_ldap_user_in_database' => false,
    /**
     * Enable registration
     * Allows users to register through the website.
     * Accepted values: boolean true or false
     */
    'enable_registration' => false,
);

/**
 * You do not need to edit below this line
 */
return array(
    'unicaen-auth' => $settings,
    'zfcuser'      => array(
        $k='enable_registration' => isset($settings[$k]) ? $settings[$k] : false,
    ),
);