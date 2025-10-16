<?php

namespace Application\Service\Perimetre;

use Application\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class  PerimetreServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PerimetreService
    {
        /**
         * @var AgentService $agentService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);

        $service = new PerimetreService();
        $service->setAgentService($agentService);
        $service->setStructureService($structureService);
        return $service;
    }
}