<?php

namespace Element\Form\ApplicationElement;

use Element\Service\Application\ApplicationService;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationElementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ApplicationElementHydrator
    {
        /**
         * @var ApplicationService $applicationService
         * @var NiveauMaitriseService $MaitriseNiveauService
         */
        $applicationService = $container->get(ApplicationService::class);
        $MaitriseNiveauService = $container->get(NiveauMaitriseService::class);

        $hydrator = new ApplicationElementHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setNiveauMaitriseService($MaitriseNiveauService);
        return $hydrator;
    }
}