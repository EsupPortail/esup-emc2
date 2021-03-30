<?php

namespace Application\Form\ApplicationElement;

use Application\Service\Application\ApplicationService;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class ApplicationElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementForm
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
        $hydrator = $container->get('HydratorManager')->get(ApplicationElementHydrator::class);

        /** @var ApplicationElementForm $form */
        $form = new ApplicationElementForm();
        $form->setApplicationService($applicationService);
        $form->setCompetenceMaitriseService($competenceMaitriseService);
        $form->setHydrator($hydrator);
        return $form;
    }
}