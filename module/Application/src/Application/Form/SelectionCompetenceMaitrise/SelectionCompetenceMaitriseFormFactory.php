<?php

namespace Application\Form\SelectionCompetenceMaitrise;

use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class SelectionCompetenceMaitriseFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionCompetenceMaitriseForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceMaitriseService $competenceMaitriseService
         * @var SelectionCompetenceMaitriseHydrator $hydrator
         */
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionCompetenceMaitriseHydrator::class);

        $form = new SelectionCompetenceMaitriseForm();
        $form->setCompetenceMaitriseService($competenceMaitriseService);
        $form->setHydrator($hydrator);
        return $form;
    }
}