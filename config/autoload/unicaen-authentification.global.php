<?php

/**
 * UnicaenAuthentification Global Configuration
 */


return [
    'unicaen-auth' => [
        /**
         * Flag indiquant si l'utilisateur authenitifié avec succès via l'annuaire LDAP doit
         * être enregistré/mis à jour dans la table des utilisateurs de l'appli.
         */
        'save_ldap_user_in_database' => true,
        'entity_manager_name' => 'doctrine.entitymanager.orm_default', // nom du gestionnaire d'entités à utiliser

        'reset_password' =>
            [
                /** Lors de reset de mot de passe, on utilise un attribut utilisé dans un findOneBy.
                 * La clé 'identification' permet de modifier celle-ci.
                 * Remarque : si absente ou laissée vide alors, on reste sur l'attribut historique qui est email.
                 */
                'identification' => 'email'
            ],
    ],

];


