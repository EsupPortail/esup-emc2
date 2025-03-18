<?php

namespace Element\Form\ApplicationElement;

use Element\Service\Application\ApplicationService;
use Element\Service\Niveau\NiveauService;
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
         * @var NiveauService $MaitriseNiveauService
         */
        $applicationService = $container->get(ApplicationService::class);
        $MaitriseNiveauService = $container->get(NiveauService::class);

        $hydrator = new ApplicationElementHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setNiveauService($MaitriseNiveauService);
        return $hydrator;
    }
}