<?php

namespace Element\Form\ApplicationElement;

use Element\Service\Application\ApplicationService;
use Element\Service\Niveau\NiveauService;
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
         * @var NiveauService $MaitriseNiveauService
         */
        $applicationService = $container->get(ApplicationService::class);
        $MaitriseNiveauService = $container->get(NiveauService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = new ApplicationElementHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setNiveauService($MaitriseNiveauService);
        return $hydrator;
    }
}