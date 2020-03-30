<?php

namespace Application\Form\AgentApplication;

use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class AgentApplicationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentApplicationHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /** @var AgentApplicationHydrator $hydrator */
        $hydrator = new AgentApplicationHydrator();
        $hydrator->setApplicationService($applicationService);
        return $hydrator;
    }
}