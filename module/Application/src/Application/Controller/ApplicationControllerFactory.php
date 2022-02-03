<?php

namespace Application\Controller;

use Application\Form\Application\ApplicationForm;
use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Service\Agent\AgentService;
use Application\Service\Application\ApplicationGroupeService;
use Application\Service\Application\ApplicationService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;

class ApplicationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         * @var ApplicationGroupeService $applicationGroupeService
         * @var ApplicationElementService $applicationElementService
         * @var AgentService $agentService
         * @var FicheMetierService $ficheMetierService
         * @var MaitriseNiveauService $maitriseNiveauService
         * @var MetierService $metierService
         */
        $applicationService = $container->get(ApplicationService::class);
        $applicationGroupeService = $container->get(ApplicationGroupeService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $agentService = $container->get(AgentService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $maitriseNiveauService = $container->get(MaitriseNiveauService::class);
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
        $controller->setApplicationGroupeService($applicationGroupeService);
        $controller->setApplicationElementService($applicationElementService);
        $controller->setAgentService($agentService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMaitriseNiveauService($maitriseNiveauService);
        $controller->setMetierService($metierService);
        $controller->setApplicationForm($applicationForm);
        $controller->setApplicationElementForm($applicationElementForm);
        return $controller;
    }

}