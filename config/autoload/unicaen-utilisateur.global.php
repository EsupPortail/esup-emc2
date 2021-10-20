<?php

use Application\Provider\IdentityProvider;
use UnicaenUtilisateur\ORM\Event\Listeners\HistoriqueListenerFactory;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenUtilisateurLdapAdapter\Service\LdapService;
//use UnicaenUtilisateurOctopusAdapter\Service\OctopusService;

return [
    /**
     * L'entité associée aux utilisateurs peut être spécifiée via la clef de configuration ['zfcuser']['user_entity_class']
     * si elle est manquante alors la classe \UnicaenUtiliseur\Entity\Db\User est utilisée (! zfcuser en besoin aussi !!!!)
     * NB: la classe spécifiée doit hériter de \UnicaenUtiliseur\Entity\Db\AbstractUser.
     */
    'zfcuser' => [
        'user_entity_class' => \UnicaenUtilisateur\Entity\Db\User::class,
    ],

    /**
     * L'entité associée aux roles peut être spécifiée via la clef de configuration ['unicaen_auth']['role_entity_class']
     * si elle est manquante alors la classe \UnicaenUtiliseur\Entity\Db\Role est utilisée
     * NB: la classe spécifiée doit hériter de \UnicaenUtiliseur\Entity\Db\AbstractRole.
     */
    'unicaen-auth' => [
        'role_entity_class' => \UnicaenUtilisateur\Entity\Db\Role::class,
    ],

    'unicaen-utilisateur' => [
        'recherche-individu' => [
            'app'       => UserService::class,
            'ldap'      => LdapService::class,
//            'octopus'   => OctopusService::class,
        ],
        'identity-provider' => IdentityProvider::class,
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
        ],
    ],
];