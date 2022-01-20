<?php

use UnicaenPrivilege\Entity\Db\Privilege;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Entity\Db\Role;

$settings = [
    /**
     * L'entité associée aux roles peut être spécifiée via la clef de configuration ['unicaen_auth']['role_entity_class']
     * si elle est manquante alors la classe \UnicaenUtiliseur\Entity\Db\Role est utilisée
     * NB: la classe spécifiée doit hériter de \UnicaenUtiliseur\Entity\Db\AbstractRole.
     */
    'unicaen-auth' => [
        'enable_privileges' => true,
        'identity_providers' => [
            300 => 'UnicaenAuthentification\Provider\Identity\Basic', // en 1er
            200 => 'UnicaenAuthentification\Provider\Identity\Db',    // en 2e
            100 => 'UnicaenAuthentification\Provider\Identity\Ldap',  // en 3e @deprecated
            001 => 'Application\Provider\IdentityProvider', // en 3e
            000 => 'EntretienProfessionnel\Provider\IdentityProvider', // en 3e
        ],
    ],
];

if ($settings['unicaen-auth']['enable_privileges']) {
    $privileges = [
        'unicaen-auth' => [
            /**
             * Classes représentant les entités rôle et privilège.
             * - Entité rôle      : héritant de \UnicaenAuth\Entity\Db\AbstractRole      ou implémentant \UnicaenAuth\Entity\Db\RoleInterface.
             * - Entité privilège : héritant de \UnicaenAuth\Entity\Db\AbstractPrivilege ou implémentant \UnicaenAuth\Entity\Db\PrivilegeInterface.
             *
             * Valeurs par défaut :
             * - 'role_entity_class'      : 'UnicaenAuth\Entity\Db\Role'
             * - 'privilege_entity_class' : 'UnicaenAuth\Entity\Db\Privilege'
             */
            'role_entity_class'      => Role::class,
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
        ]
    ];
} else {
    $privileges = [];
}

return array_merge_recursive($settings, $privileges);