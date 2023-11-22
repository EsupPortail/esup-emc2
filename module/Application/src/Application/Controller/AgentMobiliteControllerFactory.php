<?php

namespace Application\Controller;

use Application\Form\AgentMobilite\AgentMobiliteForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentMobilite\AgentMobiliteService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentMobiliteControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMobiliteController
    {
        /**
         * @var AgentService $agentService
         * @var AgentMobiliteService $agentMobiliteService
         * @var AgentMobiliteForm $agentMobiliteForm
         */
        $agentService = $container->get(AgentService::class);
        $agentMobiliteService = $container->get(AgentMobiliteService::class);
        $agentMobiliteForm = $container->get('FormElementManager')->get(AgentMobiliteForm::class);

        $controller = new AgentMobiliteController();
        $controller->setAgentService($agentService);
        $controller->setAgentMobiliteService($agentMobiliteService);
        $controller->setAgentMobiliteForm($agentMobiliteForm);
        return $controller;
    }
}