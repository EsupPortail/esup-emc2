<?php

namespace Application\Form\ApplicationElement;

use Application\Service\Application\ApplicationService;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
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
         * @var CompetenceMaitriseService $competenceMaitriseService
         */
        $applicationService = $container->get(ApplicationService::class);
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);

        /** @var ApplicationElementHydrator $hydrator */
        $hydrator = new ApplicationElementHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setCompetenceMaitriseService($competenceMaitriseService);
        return $hydrator;
    }
}