<?php

namespace Application\Form\ApplicationElement;

use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class ApplicationElementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = new ApplicationElementHydrator();
        $hydrator->setApplicationService($applicationService);
        return $hydrator;
    }
}