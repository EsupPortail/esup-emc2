<?php

namespace Application\Controller;

use Application\Form\AgentMobilite\AgentMobiliteForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentMobilite\AgentMobiliteService;
use Carriere\Service\Mobilite\MobiliteService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

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
         * @var MobiliteService $mobiliteService
         * @var StructureService $structureService
         * @var AgentMobiliteForm $agentMobiliteForm
         */
        $agentService = $container->get(AgentService::class);
        $agentMobiliteService = $container->get(AgentMobiliteService::class);
        $mobiliteService = $container->get(MobiliteService::class);
        $structureService = $container->get(StructureService::class);
        $agentMobiliteForm = $container->get('FormElementManager')->get(AgentMobiliteForm::class);

        $controller = new AgentMobiliteController();
        $controller->setAgentService($agentService);
        $controller->setAgentMobiliteService($agentMobiliteService);
        $controller->setMobiliteService($mobiliteService);
        $controller->setStructureService($structureService);
        $controller->setAgentMobiliteForm($agentMobiliteForm);
        return $controller;
    }
}