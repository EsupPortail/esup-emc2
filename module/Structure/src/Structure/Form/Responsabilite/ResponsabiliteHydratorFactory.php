<?php

namespace Structure\Form\Responsabilite;

use Agent\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class ResponsabiliteHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ResponsabiliteHydrator
    {
        /**
         * @var AgentService $agentService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);

        $hydrator = new ResponsabiliteHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }
}