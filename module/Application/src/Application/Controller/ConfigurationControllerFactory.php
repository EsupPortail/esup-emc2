<?php

namespace Application\Controller;

use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierForm;
use Application\Service\Application\ApplicationService;
use Application\Service\Competence\CompetenceService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\FicheMetier\FicheMetierService;
use Formation\Service\Formation\FormationService;
use Autoform\Service\Formulaire\FormulaireService;
use Interop\Container\ContainerInterface;

class ConfigurationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ConfigurationService $configurationService
         * @var ApplicationService $applicationService
         * @var CompetenceService $competenceService
         * @var FormationService $formationService
         * @var FicheMetierService $ficheMetierService
         * @®ar FormulaireService $formulaireService
         * @var ConfigurationFicheMetierForm $configurationFicheMetierForm
         */
        $configurationService = $container->get(ConfigurationService::class);
        $applicationService = $container->get(ApplicationService::class);
        $competenceService = $container->get(CompetenceService::class);
        $formationService = $container->get(FormationService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $configurationFicheMetierForm = $container->get('FormElementManager')->get(ConfigurationFicheMetierForm::class);

        /** @var ConfigurationController $controller */
        $controller = new ConfigurationController();
        $controller->setConfigurationService($configurationService);
        $controller->setApplicationService($applicationService);
        $controller->setCompetenceService($competenceService);
        $controller->setFormationService($formationService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFormulaireService($formulaireService);
        $controller->setConfigurationFicheMetierForm($configurationFicheMetierForm);
        return $controller;
    }
}
