<?php

namespace Element\Form\SelectionCompetence;

use Element\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class SelectionCompetenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionCompetenceForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         */
        $competenceService = $container->get(CompetenceService::class);

        /**
         * @var SelectionCompetenceHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionCompetenceHydrator::class);

        /** @var SelectionCompetenceForm $form */
        $form = new SelectionCompetenceForm();
        $form->setHydrator($hydrator);
        $form->setCompetenceService($competenceService);
        return $form;
    }
}