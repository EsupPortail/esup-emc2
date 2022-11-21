<?php

/**
 * UnicaenAuthentification Global Configuration
 */

return [
    'unicaen-auth' =>  [
        /**
         * Flag indiquant si l'utilisateur authenitifié avec succès via l'annuaire LDAP doit
         * être enregistré/mis à jour dans la table des utilisateurs de l'appli.
         */
        'save_ldap_user_in_database' => true,
        'entity_manager_name' => 'doctrine.entitymanager.orm_default', // nom du gestionnaire d'entités à utiliser

        /**
         * Attribut LDAP utilisé pour le username des utilisateurs
         * A personnaliser au besoin
         */
//        'ldap_username' => 'uid',
//        'ldap_username' => 'supannaliaslogin',

    ],
];


