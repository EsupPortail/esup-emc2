<?php

namespace Element\Form\ApplicationElement;

use Element\Service\Application\ApplicationService;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
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
         * @var MaitriseNiveauService $MaitriseNiveauService
         */
        $applicationService = $container->get(ApplicationService::class);
        $MaitriseNiveauService = $container->get(MaitriseNiveauService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = new ApplicationElementHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setMaitriseNiveauService($MaitriseNiveauService);
        return $hydrator;
    }
}