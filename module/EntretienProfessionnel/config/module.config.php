<?php

namespace EntretienProfessionnel;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use EntretienProfessionnel\Provider\Identity\IdentityProvider;
use EntretienProfessionnel\Provider\Identity\IdentityProviderFactory;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteServiceFactory;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurServiceFactory;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelEntretienProfessionnelServiceFactory;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationService;
use EntretienProfessionnel\Service\Evenement\RappelPasObservationServiceFactory;
use EntretienProfessionnel\Service\Notification\NotificationService;
use EntretienProfessionnel\Service\Notification\NotificationServiceFactory;
use EntretienProfessionnel\Service\Url\UrlService;
use EntretienProfessionnel\Service\Url\UrlServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'EntretienProfessionnel\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/EntretienProfessionnel/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__Formation__' . __NAMESPACE__,
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            IdentityProvider::class => IdentityProviderFactory::class,
            NotificationService::class => NotificationServiceFactory::class,
            UrlService::class => UrlServiceFactory::class,

            RappelCampagneAvancementAutoriteService::class => RappelCampagneAvancementAutoriteServiceFactory::class,
            RappelCampagneAvancementSuperieurService::class => RappelCampagneAvancementSuperieurServiceFactory::class,
            RappelEntretienProfessionnelService::class => RappelEntretienProfessionnelServiceFactory::class,
            RappelPasObservationService::class => RappelPasObservationServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
        ]
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];
