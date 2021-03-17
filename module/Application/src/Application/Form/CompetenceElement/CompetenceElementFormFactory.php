<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class CompetenceElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceMaitriseService $competenceMaitriseService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceElementHydrator::class);

        /** @var CompetenceElementForm $form */
        $form = new CompetenceElementForm();
        $form->setCompetenceService($competenceService);
        $form->setCompetenceMaitriseService($competenceMaitriseService);
        $form->setHydrator($hydrator);
        return $form;
    }
}