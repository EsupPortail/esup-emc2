<?php
/**
 * UnicaenAuth Global Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */

use Application\Provider\IdentityProvider;
use Application\Provider\IdentityProviderFactory;
use UnicaenAuthentification\Provider\Identity\Chain;
use UnicaenAuthentification\Provider\Role\DbRole;
use UnicaenAuthentification\Provider\Role\Username;
use UnicaenPrivilege\Entity\Db\Privilege;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;

$settings = [
    /**
     * Flag indiquant si l'utilisateur authenitifié avec succès via l'annuaire LDAP doit
     * être enregistré/mis à jour dans la table des utilisateurs de l'appli.
     */
    'save_ldap_user_in_database' => true,
    'enable_privileges' => true,
    'entity_manager_name' => 'doctrine.entitymanager.orm_default', // nom du gestionnaire d'entités à utiliser
    'identity_providers'  => [
        300 => 'UnicaenAuthentification\Provider\Identity\Basic',   // en 1er
        200 => 'UnicaenAuthentification\Provider\Identity\Db',      // en 2e
        100 => 'Application\Provider\IdentityProvider', // en 3e
    ],
];

$config = [
    'unicaen-auth' => $settings,
    'bjyauthorize' => [
        'identity_provider' => Chain::class,
        'role_providers'    => [
            /**
             * Fournit les rôles issus de la base de données éventuelle de l'appli.
             * NB: si le rôle par défaut 'guest' est fourni ici, il ne sera pas ajouté en double dans les ACL.
             * NB: si la connexion à la base échoue, ce n'est pas bloquant!
             */
            DbRole::class => [],
            /**
             * Fournit le rôle correspondant à l'identifiant de connexion de l'utilisateur.
             * Cela est utile lorsque l'on veut gérer les habilitations d'un utilisateur unique
             * sur des ressources.
             */
            Username::class => [],
        ],
    ],
    'zfcuser'      => [
        /**
         * Enable registration
         * Allows users to register through the website.
         * Accepted values: boolean true or false
         */
        'enable_registration' => false,
    ],
    'service_manager' => [
        'factories' => [
            IdentityProvider::class => IdentityProviderFactory::class,
        ],
    ],
];

if ($settings['enable_privileges']) {
    $privileges = [
        'unicaen-auth' => [
            /**
             * - Entité rôle      : héritant de \UnicaenUtilisateur\Entity\Db\AbstractRole      ou implémentant \UnicaenUtilisateur\Entity\Db\RoleInterface.
             * - Entité privilège : héritant de \UnicaenPrivilege\Entity\Db\AbstractPrivilege   ou implémentant \UnicaenPrivilege\Entity\Db\PrivilegeInterface.
             */
            //'role_entity_class'      => Role::class,
            'privilege_entity_class' => Privilege::class,
        ],

        'bjyauthorize' => [

            'resource_providers' => [
                /**
                 * Le service Privilèges peut aussi être une source de ressources,
                 * si on souhaite tester directement l'accès à un privilège
                 */
                PrivilegeService::class => [],
            ],

            'rule_providers'     => [
                PrivilegeRuleProvider::class => [],
            ],
        ],
    ];
} else {
    $privileges = [];
}

return array_merge_recursive($config, $privileges);