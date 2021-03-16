<?php

namespace Application\Form\CompetenceMaitrise;

use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class CompetenceMaitriseFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceMaitriseForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceMaitriseService $competenceMaitriseService
         * @var CompetenceMaitriseHydrator $competenceMaitriseHydrator
         */
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);
        $competenceMaitriseHydrator = $container->get('HydratorManager')->get(CompetenceMaitriseHydrator::class);

        $form = new CompetenceMaitriseForm();
        $form->setCompetenceMaitriseService($competenceMaitriseService);
        $form->setHydrator($competenceMaitriseHydrator);
        return $form;
    }
}