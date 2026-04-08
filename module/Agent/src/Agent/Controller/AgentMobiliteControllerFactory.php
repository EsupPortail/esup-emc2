<?php

namespace Agent\Controller;

use Agent\Form\AgentMobilite\AgentMobiliteForm;
use Agent\Service\Agent\AgentService;
use Agent\Service\AgentMobilite\AgentMobiliteService;
use Carriere\Service\Mobilite\MobiliteService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;

class AgentMobiliteControllerFactory
{

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
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         * @var AgentMobiliteForm $agentMobiliteForm
         */
        $agentService = $container->get(AgentService::class);
        $agentMobiliteService = $container->get(AgentMobiliteService::class);
        $mobiliteService = $container->get(MobiliteService::class);
        $parametresService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);
        $agentMobiliteForm = $container->get('FormElementManager')->get(AgentMobiliteForm::class);

        $controller = new AgentMobiliteController();
        $controller->setAgentService($agentService);
        $controller->setAgentMobiliteService($agentMobiliteService);
        $controller->setMobiliteService($mobiliteService);
        $controller->setParametreService($parametresService);
        $controller->setStructureService($structureService);
        $controller->setAgentMobiliteForm($agentMobiliteForm);
        return $controller;
    }
}