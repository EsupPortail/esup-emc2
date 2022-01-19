<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Configuration\ConfigurationService;
use EntretienProfessionnel\Form\ConfigurationRecopie\ConfigurationRecopieForm;
use Interop\Container\ContainerInterface;

class ConfigurationControllerFactory {

    /**
     * @var ContainerInterface $container
     * @return ConfigurationController
     */
    public function __invoke(ContainerInterface $container) : ConfigurationController
    {
        /**
         * @var ConfigurationService $configurationService
         */
        $configurationService = $container->get(ConfigurationService::class);

        /**
         * @var ConfigurationRecopieForm $configurationRecopieForm
         */
        $configurationRecopieForm = $container->get('FormElementManager')->get(ConfigurationRecopieForm::class);

        $controller = new ConfigurationController();
        $controller->setConfigurationService($configurationService);
        $controller->setConfigurationRecopieForm($configurationRecopieForm);
        return $controller;
    }
}