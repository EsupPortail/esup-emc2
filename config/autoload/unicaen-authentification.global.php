<?php

/**
 * UnicaenAuthentification Global Configuration
 */

use Application\View\Helper\TestConnectViewHelper;
use Application\View\Helper\TestConnectViewHelperFactory;
use Laminas\Authentication\Adapter\Ldap;
use UnicaenAuthentification\Form\LoginForm;

return [
    'unicaen-auth' =>  [
        /**
         * Flag indiquant si l'utilisateur authenitifié avec succès via l'annuaire LDAP doit
         * être enregistré/mis à jour dans la table des utilisateurs de l'appli.
         */
        'save_ldap_user_in_database' => true,
        'entity_manager_name' => 'doctrine.entitymanager.orm_default', // nom du gestionnaire d'entités à utiliser

        /**
         * Attribut LDAP utilisé pour le "username" des utilisateurs
         * À personnaliser au besoin
         */
//        'ldap_username' => 'uid',
//        'ldap_username' => 'supannaliaslogin',

        'auth_types' => [
            'local', // càd 'ldap' et 'db'
            'cas',
            'shib',
            'test',
        ],

        'test' => [
            'enabled' => false,
            //'adapter' => Ldap::class,
            'form' => LoginForm::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'testConnect' => TestConnectViewHelper::class,
        ],
        'factories' => [
            TestConnectViewHelper::class => TestConnectViewHelperFactory::class,
        ],
    ],
];


