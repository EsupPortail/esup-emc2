<?php

use Application\Provider\IdentityProvider;
use Application\Provider\IdentityProviderFactory;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\ORM\Event\Listeners\HistoriqueListenerFactory;
use UnicaenUtilisateur\Provider\Privilege\RolePrivileges;
use UnicaenUtilisateur\Provider\Privilege\UtilisateurPrivileges;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenUtilisateurLdapAdapter\Service\LdapService;

return [
    /**
     * L'entité associée aux utilisateurs peut être spécifiée via la clef de configuration ['zfcuser']['user_entity_class']
     * si elle est manquante alors la classe \UnicaenUtiliseur\Entity\Db\User est utilisée (! zfcuser en besoin aussi !!!!)
     * NB: la classe spécifiée doit hériter de \UnicaenUtiliseur\Entity\Db\AbstractUser.
     */
    'zfcuser' => [
        'user_entity_class' => User::class,
        /**
         * Enable registration
         * Allows users to register through the website.
         * Accepted values: boolean true or false
         */
        'enable_registration' => true,
    ],

    'bjyauthorize' => [
        /* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         *
         * for ZfcUser, this will be your default identity provider
         */
        'identity_provider' => 'UnicaenAuthentification\Provider\Identity\Chain',

        /* role providers simply provide a list of roles that should be inserted
         * into the Laminas\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Laminas\Db adapter.
         */
        'role_providers'    => [
            /**
             * Fournit les rôles issus de la base de données éventuelle de l'appli.
             * NB: si le rôle par défaut 'guest' est fourni ici, il ne sera pas ajouté en double dans les ACL.
             * NB: si la connexion à la base échoue, ce n'est pas bloquant!
             */
            UnicaenUtilisateur\Provider\Role\DbRole::class   => [],
            /**
             * Fournit le rôle correspondant à l'identifiant de connexion de l'utilisateur.
             * Cela est utile lorsque l'on veut gérer les habilitations d'un utilisateur unique
             * sur des ressources.
             */
            'UnicaenUtilisateur\Provider\Role\Username' => [],
        ],
    ],

    /**
     * L'entité associée aux roles peut être spécifiée via la clef de configuration ['unicaen_auth']['role_entity_class']
     * si elle est manquante alors la classe \UnicaenUtiliseur\Entity\Db\Role est utilisée
     * NB: la classe spécifiée doit hériter de \UnicaenUtiliseur\Entity\Db\AbstractRole.
     */
    'unicaen-auth' => [
        'role_entity_class' => Role::class,
    ],

    'unicaen-utilisateur' => [
        'recherche-individu' => [
            'app'       => UserService::class,
            'ldap'      => LdapService::class,
//            'octopus'   => OctopusService::class,
        ],
        'identity-provider' => [
            IdentityProvider::class,
            \Structure\Provider\IdentityProvider::class,
        ],
        'application-username' => 'preecog',
        'default-user' => 0,
    ],

    // pour la mise a jour des champs d'historisation ...
    'doctrine' => [
        'eventmanager'  => [
            'orm_default' => [
                'subscribers' => [
                    'UnicaenUtilisateur\HistoriqueListener',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'UnicaenUtilisateur\HistoriqueListener' => HistoriqueListenerFactory::class,
            IdentityProvider::class => IdentityProviderFactory::class
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaen-utilisateur' =>
                            [
                                'label' => 'Gestion des rôles et utilisateurs',
                                'route' => 'unicaen-utilisateur',
                                'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AFFICHER),
                                'order'    => 20000,
                                'dropdown-header' => true,
                            ],
                            'utilisateur' => [
                                'label' => 'Utilisateurs',
                                'route' => 'unicaen-utilisateur',
                                'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AFFICHER),
                                'order'    => 20200,
                                'icon' => 'fas fa-angle-right',
                                'pages' => [
                                    'listing-utilisateur' => [
                                        'label' => 'Listing',
                                        'route' => 'unicaen-utilisateur/lister',
                                        'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AFFICHER),
                                        'order'    => 20210,
                                    ],
                                    'ajouter-utilisateur' => [
                                        'label' => 'Listing',
                                        'route' => 'unicaen-utilisateur/ajouter',
                                        'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AJOUTER),
                                        'order'    => 20220,
                                    ],
                                ],
                            ],
                            'role' => [
                                'label' => 'Rôles',
                                'route' => 'unicaen-role',
                                'resource' => RolePrivileges::getResourceId(RolePrivileges::ROLE_AFFICHER),
                                'order'    => 20100,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

//    'navigation' => [
//        'default' => [
//            'home' => [
//                'pages' => [
//                    'administration' => [
//                        'pages' => [
//                            [
//                                'label' => 'Gérer les utilisateurs',
//                                'title' => 'Gérer les utilisateurs',
//                                'route' => 'unicaen-utilisateur',
//                                'resource' => UtilisateurPrivileges::getResourceId(UtilisateurPrivileges::UTILISATEUR_AFFICHER),
//                            ],
//                            [
//                                'label' => 'Gérer les rôles',
//                                'title' => 'Gérer les rôles',
//                                'route' => 'unicaen-role',
//                                'resource' => RolePrivileges::getResourceId(RolePrivileges::ROLE_AFFICHER),
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],


];