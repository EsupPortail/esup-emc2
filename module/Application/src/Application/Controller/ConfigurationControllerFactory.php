<?php

namespace Application\Controller;

use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierForm;
use Application\Service\Configuration\ConfigurationService;
use Element\Service\Application\ApplicationService;
use Element\Service\Competence\CompetenceService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireService;

class ConfigurationControllerFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ConfigurationController
    {
        /**
         * @var ConfigurationService $configurationService
         * @var ApplicationService $applicationService
         * @var CompetenceService $competenceService
         * @var FicheMetierService $ficheMetierService
         * @var FormulaireService $formulaireService
         * @var ConfigurationFicheMetierForm $configurationFicheMetierForm
         */
        $configurationService = $container->get(ConfigurationService::class);
        $applicationService = $container->get(ApplicationService::class);
        $competenceService = $container->get(CompetenceService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $configurationFicheMetierForm = $container->get('FormElementManager')->get(ConfigurationFicheMetierForm::class);

        $controller = new ConfigurationController();
        $controller->setConfigurationService($configurationService);
        $controller->setApplicationService($applicationService);
        $controller->setCompetenceService($competenceService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFormulaireService($formulaireService);
        $controller->setConfigurationFicheMetierForm($configurationFicheMetierForm);
        return $controller;
    }
}
