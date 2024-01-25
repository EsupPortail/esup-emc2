<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Configuration\ConfigurationService;
use EntretienProfessionnel\Form\ConfigurationRecopie\ConfigurationRecopieForm;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireService;

class ConfigurationControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ConfigurationController
    {
        /**
         * @var ConfigurationService $configurationService
         * @var FormulaireService $formulaireService
         */
        $configurationService = $container->get(ConfigurationService::class);
        $formulaireService = $container->get(FormulaireService::class);

        /**
         * @var ConfigurationRecopieForm $configurationRecopieForm
         */
        $configurationRecopieForm = $container->get('FormElementManager')->get(ConfigurationRecopieForm::class);

        $controller = new ConfigurationController();
        $controller->setConfigurationService($configurationService);
        $controller->setFormulaireService($formulaireService);
        $controller->setConfigurationRecopieForm($configurationRecopieForm);
        return $controller;
    }
}