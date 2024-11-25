<?php

namespace Application\Form\Chaine;

use Application\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChaineHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ChaineHydrator
    {
        /** @var AgentService $agentService */
        $agentService = $container->get(AgentService::class);

        $hydrator = new ChaineHydrator();
        $hydrator->setAgentService($agentService);
        return $hydrator;
    }
}