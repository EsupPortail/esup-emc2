<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
//use UnicaenUtilisateur\Event\UserAuthenticatedEventListenerFactory;
use UnicaenUtilisateur\Event\UserRoleSelectedEventListener;
use UnicaenUtilisateur\ORM\Event\Listeners\HistoriqueListener;
use UnicaenUtilisateur\ORM\Event\Listeners\HistoriqueListenerFactory;
use UnicaenUtilisateur\View\Helper\UserCurrent;
use UnicaenUtilisateur\View\Helper\UserCurrentFactory;
use UnicaenUtilisateur\View\Helper\UserInfo;
use UnicaenUtilisateur\View\Helper\UserInfoFactory;
use UnicaenUtilisateur\View\Helper\UserProfile;
use UnicaenUtilisateur\View\Helper\UserProfileFactory;
use UnicaenUtilisateur\View\Helper\UserProfileSelect;
use UnicaenUtilisateur\View\Helper\UserProfileSelectFactory;
use UnicaenUtilisateur\View\Helper\UserProfileSelectRadioItem;
use UnicaenUtilisateur\View\Helper\UserProfileSelectRadioItemFactory;
use UnicaenUtilisateur\View\Helper\UserStatus;
use UnicaenUtilisateur\View\Helper\UserStatusFactory;

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'UnicaenUtilisateur\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/UnicaenUtilisateur/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'UNICAEN-UTILISATEUR__' . __NAMESPACE__,
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'UserRoleSelectedEventListener' => UserRoleSelectedEventListener::class,
            ],
        'factories' => [
//            'UserAuthenticatedEventListener' => UserAuthenticatedEventListenerFactory::class,
            HistoriqueListener::class => HistoriqueListenerFactory::class,
        ],
    ],

    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'view_helpers'  => [
        'aliases' => [
            'userCurrent'                => UserCurrent::class,
            'userStatus'                 => UserStatus::class,
            'userProfile'                => UserProfile::class,
            'userInfo'                   => UserInfo::class,
            'userProfileSelect'          => UserProfileSelect::class,
            'userProfileSelectRadioItem' => UserProfileSelectRadioItem::class,
        ],
        'factories' => [
            UserCurrent::class                => UserCurrentFactory::class,
            UserStatus::class                 => UserStatusFactory::class,
            UserProfile::class                => UserProfileFactory::class,
            UserInfo::class                   => UserInfoFactory::class,
            UserProfileSelect::class          => UserProfileSelectFactory::class,
            UserProfileSelectRadioItem::class => UserProfileSelectRadioItemFactory::class,
        ],
    ],
];
