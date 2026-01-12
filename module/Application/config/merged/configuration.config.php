<?php

namespace Application;

use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierForm;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierFormFactory;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierHydrator;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierHydratorFactory;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\Configuration\ConfigurationServiceFactory;

return [
    'service_manager' => [
        'factories' => [
            ConfigurationService::class => ConfigurationServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            ConfigurationFicheMetierForm::class => ConfigurationFicheMetierFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            ConfigurationFicheMetierHydrator::class => ConfigurationFicheMetierHydratorFactory::class,
        ],
    ]
];
