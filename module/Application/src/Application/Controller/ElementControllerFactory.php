<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Element\Form\ApplicationElement\ApplicationElementForm;
use Element\Form\CompetenceElement\CompetenceElementForm;
use Element\Form\SelectionNiveau\SelectionNiveauForm;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ElementControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ElementController
    {
        /**
         * @var AgentService $agentService
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var FicheMetierService $ficheMetierService
         * @var NiveauMaitriseService $niveauMaitriseService
         */
        $agentService = $container->get(AgentService::class);
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $niveauMaitriseService = $container->get(NiveauMaitriseService::class);

        /**
         * @var ApplicationElementForm $applicationElementForm
         * @var CompetenceElementForm $competenceElementForm
         * @var SelectionNiveauForm $selectionMaitriseForm
         */
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);
        $competenceElementForm = $container->get('FormElementManager')->get(CompetenceElementForm::class);
        $selectionMaitriseForm = $container->get('FormElementManager')->get(SelectionNiveauForm::class);

        $controller = new ElementController();
        $controller->setAgentService($agentService);
        $controller->setApplicationService($applicationService);
        $controller->setApplicationElementService($applicationElementService);
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setNiveauMaitriseService($niveauMaitriseService);
        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementForm($competenceElementForm);
        $controller->setSelectionNiveauForm($selectionMaitriseForm);
        return $controller;
    }
}