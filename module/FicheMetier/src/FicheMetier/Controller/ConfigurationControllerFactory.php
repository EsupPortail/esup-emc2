<?php

namespace FicheMetier\Controller;

use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierForm;
use Application\Service\Configuration\ConfigurationService;
use Element\Service\Application\ApplicationService;
use Element\Service\Competence\CompetenceService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class ConfigurationControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ConfigurationController
    {
        /**
         * @var ApplicationService $applicationService
         * @var CompetenceService $competenceService
         * @var ConfigurationService $configurationService // todo à deplacer
         * @var FicheMetierService $ficheMetierService
         * @var ParametreServiceAwareTrait $parametreService
         */
        $applicationService = $container->get(ApplicationService::class);
        $competenceService = $container->get(CompetenceService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var ConfigurationFicheMetierForm $configurationForm
         */
        $configurationForm = $container->get('FormElementManager')->get(ConfigurationFicheMetierForm::class);

        $controller = new ConfigurationController();
        $controller->setApplicationService($applicationService);
        $controller->setCompetenceService($competenceService);
        $controller->setConfigurationService($configurationService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setParametreService($parametreService);
        $controller->setConfigurationFicheMetierForm($configurationForm);

        return $controller;
    }
}
