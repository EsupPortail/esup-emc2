<?php

namespace Application\Controller;

use Application\Form\AgentStageObservation\AgentStageObservationForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentStageObservation\AgentStageObservationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentStageObservationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationController
    {
        /**
         * @var AgentService $agentService
         * @var AgentStageObservationService $agentStageObservationService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         *
         * @var AgentStageObservationForm $agentStageObservationForm
         */
        $agentService = $container->get(AgentService::class);
        $agentStageObservationService = $container->get(AgentStageObservationService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $agentStageObservationForm = $container->get('FormElementManager')->get(AgentStageObservationForm::class);

        $controller = new AgentStageObservationController();
        $controller->setAgentService($agentService);
        $controller->setAgentStageObservationService($agentStageObservationService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setAgentStageObservationForm($agentStageObservationForm);
        return $controller;
    }
}