<?php

namespace Application\Controller;

use Application\Form\AgentPPP\AgentPPPForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentPPP\AgentPPPService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentPppControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPppController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentPppController
    {
        /**
         * @var AgentService $agentService
         * @var AgentPPPService $agentPppService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         *
         * @var AgentPPPForm $agentPppForm
         */
        $agentService = $container->get(AgentService::class);
        $agentPppService = $container->get(AgentPppService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $agentPppForm = $container->get('FormElementManager')->get(AgentPPPForm::class);

        $controller = new AgentPppController();
        $controller->setAgentService($agentService);
        $controller->setAgentPPPService($agentPppService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setAgentPPPForm($agentPppForm);
        return $controller;
    }
}