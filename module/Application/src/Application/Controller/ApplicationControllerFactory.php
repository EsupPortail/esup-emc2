<?php

namespace Application\Controller;

use Application\Form\Application\ApplicationForm;
use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\ApplicationGroupe\ApplicationGroupeForm;
use Application\Form\SelectionCompetenceMaitrise\SelectionCompetenceMaitriseForm;
use Application\Service\Agent\AgentService;
use Application\Service\Application\ApplicationGroupeService;
use Application\Service\Application\ApplicationService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;

class ApplicationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         * @var ApplicationGroupeService $applicationGroupeService
         * @var ApplicationElementService $applicationElementService
         * @var AgentService $agentService
         * @var FicheMetierService $ficheMetierService
         */
        $applicationService = $container->get(ApplicationService::class);
        $applicationGroupeService = $container->get(ApplicationGroupeService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $agentService = $container->get(AgentService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        /**
         * @var ApplicationForm $applicationForm
         * @var ApplicationGroupeForm $applicationGroupeForm
         * @var SelectionCompetenceMaitriseForm $selectionMaitriseForm
         * @var ApplicationElementForm $applicationElementForm
         */
        $applicationForm = $container->get('FormElementManager')->get(ApplicationForm::class);
        $applicationGroupeForm = $container->get('FormElementManager')->get(ApplicationGroupeForm::class);
        $selectionMaitriseForm = $container->get('FormElementManager')->get(SelectionCompetenceMaitriseForm::class);
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);

        /** @var ApplicationController $controller */
        $controller = new ApplicationController();
        $controller->setApplicationService($applicationService);
        $controller->setApplicationGroupeService($applicationGroupeService);
        $controller->setApplicationElementService($applicationElementService);
        $controller->setAgentService($agentService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setApplicationForm($applicationForm);
        $controller->setApplicationGroupeForm($applicationGroupeForm);
        $controller->setSelectionCompetenceMaitriseForm($selectionMaitriseForm);
        $controller->setApplicationElementForm($applicationElementForm);
        return $controller;
    }
}