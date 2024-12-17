<?php

namespace Agent\Form\AgentMobilite;

use Application\Service\Agent\AgentService;
use Carriere\Service\Mobilite\MobiliteService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentMobiliteHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMobiliteHydrator
    {
        /**
         * @var AgentService $agentService
         * @var MobiliteService $mobiliteService
         */
        $agentService = $container->get(AgentService::class);
        $mobiliteService = $container->get(MobiliteService::class);

        $hydrator = new AgentMobiliteHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setMobiliteService($mobiliteService);
        return $hydrator;
    }
}