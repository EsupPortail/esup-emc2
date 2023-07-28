<?php

namespace Application\Controller;

use Application\Form\AgentStageObservation\AgentStageObservationForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentStageObservation\AgentStageObservationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentStageObservationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentStageObservationController
    {
        /**
         * @var AgentService $agentService
         * @var AgentStageObservationService $agentStageObservationService
         * @var EtatCategorieService $etatCategorieService
         * @var EtatTypeService $etatTypeService
         * @var AgentStageObservationForm $agentStageObservationForm
         */
        $agentService = $container->get(AgentService::class);
        $agentStageObservationService = $container->get(AgentStageObservationService::class);
        $etatCategorieService = $container->get(EtatCategorieService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $agentStageObservationForm = $container->get('FormElementManager')->get(AgentStageObservationForm::class);

        $controller = new AgentStageObservationController();
        $controller->setAgentService($agentService);
        $controller->setAgentStageObservationService($agentStageObservationService);
        $controller->setEtatCategorieService($etatCategorieService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setAgentStageObservationForm($agentStageObservationForm);
        return $controller;
    }
}