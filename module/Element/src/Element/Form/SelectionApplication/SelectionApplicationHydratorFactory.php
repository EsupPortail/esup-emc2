<?php

namespace Element\Form\SelectionApplication;

use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionApplicationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionApplicationHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionApplicationHydrator
    {
        /**
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         */
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);

        $hydrator = new SelectionApplicationHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setApplicationElementService($applicationElementService);
        return $hydrator;
    }
}