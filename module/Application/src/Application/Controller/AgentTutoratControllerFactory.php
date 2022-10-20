<?php

namespace Application\Controller;

use Application\Form\AgentTutorat\AgentTutoratForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentTutorat\AgentTutoratService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentTutoratControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratController
    {
        /**
         * @var AgentService $agentService
         * @var AgentTutoratService $agentTutoratService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         *
         * @var AgentTutoratForm $agentTutoratForm
         */
        $agentService = $container->get(AgentService::class);
        $agentTutoratService = $container->get(AgentTutoratService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $agentTutoratForm = $container->get('FormElementManager')->get(AgentTutoratForm::class);

        $controller = new AgentTutoratController();
        $controller->setAgentService($agentService);
        $controller->setAgentTutoratService($agentTutoratService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setAgentTutoratForm($agentTutoratForm);

        return $controller;
    }
}