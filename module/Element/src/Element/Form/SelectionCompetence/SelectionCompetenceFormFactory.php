<?php

namespace Element\Form\SelectionCompetence;

use Element\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionCompetenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionCompetenceForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionCompetenceForm
    {
        /**
         * @var CompetenceService $competenceService
         */
        $competenceService = $container->get(CompetenceService::class);

        /**
         * @var SelectionCompetenceHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionCompetenceHydrator::class);

        $form = new SelectionCompetenceForm();
        $form->setHydrator($hydrator);
        $form->setCompetenceService($competenceService);
        return $form;
    }
}