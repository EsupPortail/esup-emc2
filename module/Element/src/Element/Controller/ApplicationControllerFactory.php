<?php

namespace Element\Controller;

use Application\Service\Agent\AgentService;
use Element\Form\Application\ApplicationForm;
use Element\Form\ApplicationElement\ApplicationElementForm;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\ApplicationTheme\ApplicationThemeService;
use Element\Service\Niveau\NiveauService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Metier\MetierService;

class ApplicationControllerFactory {

    public function __invoke(ContainerInterface $container) : ApplicationController
    {
        /**
         * @var ApplicationService $applicationService
         * @var ApplicationThemeService $applicationGroupeService
         * @var ApplicationElementService $applicationElementService
         * @var AgentService $agentService
         * @var FicheMetierService $ficheMetierService
         * @var NiveauService $maitriseNiveauService
         * @var MetierService $metierService
         */
        $applicationService = $container->get(ApplicationService::class);
        $applicationGroupeService = $container->get(ApplicationThemeService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $agentService = $container->get(AgentService::class);
        $domaineService = $container->get(DomaineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $maitriseNiveauService = $container->get(NiveauService::class);
        $metierService = $container->get(MetierService::class);

        /**
         * @var ApplicationForm $applicationForm
         * @var ApplicationElementForm $applicationElementForm
         */
        $applicationForm = $container->get('FormElementManager')->get(ApplicationForm::class);
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);

        /** @var ApplicationController $controller */
        $controller = new ApplicationController();
        $controller->setApplicationService($applicationService);
        $controller->setApplicationThemeService($applicationGroupeService);
        $controller->setApplicationElementService($applicationElementService);
        $controller->setAgentService($agentService);
        $controller->setDomaineService($domaineService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setNiveauService($maitriseNiveauService);
        $controller->setMetierService($metierService);
        $controller->setApplicationForm($applicationForm);
        $controller->setApplicationElementForm($applicationElementForm);
        return $controller;
    }

}