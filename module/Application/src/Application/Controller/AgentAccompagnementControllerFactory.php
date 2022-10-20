<?php

namespace Application\Controller;

use Application\Form\AgentAccompagnement\AgentAccompagnementForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAccompagnement\AgentAccompagnementService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentAccompagnementControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAccompagnementService $agentAccompagnementService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         *
         * @var AgentAccompagnementForm $agentAccompagnementForm
         */
        $agentService = $container->get(AgentService::class);
        $agentAccompagnementService = $container->get(AgentAccompagnementService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $agentAccompagnementForm = $container->get('FormElementManager')->get(AgentAccompagnementForm::class);

        $controller = new AgentAccompagnementController();
        $controller->setAgentService($agentService);
        $controller->setAgentAccompagnementService($agentAccompagnementService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setAgentAccompagnementForm($agentAccompagnementForm);

        return $controller;
    }
}