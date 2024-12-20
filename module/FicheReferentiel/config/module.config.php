<?php

namespace FicheReferentiel;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use FicheReferentiel\Form\Importation\ImportationForm;
use FicheReferentiel\Form\Importation\ImportationFormFactory;
use FicheReferentiel\Form\Importation\ImportationHydrator;
use FicheReferentiel\Form\Importation\ImportationHydratorFactory;
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
                    'FicheReferentiel\Entity\Db' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/FicheReferentiel/Entity/Db/Mapping',
                ],
            ],
        ],
        'cache' => [
            'apc' => [
                'namespace' => 'PREECOG__FicheReferentiel__' . __NAMESPACE__,
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
        ]
    ],
    'form_elements' => [
        'factories' => [
            ImportationForm::class => ImportationFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ImportationHydrator::class => ImportationHydratorFactory::class,
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
