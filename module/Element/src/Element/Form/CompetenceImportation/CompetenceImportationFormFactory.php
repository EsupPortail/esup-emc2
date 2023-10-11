<?php

namespace Element\Form\CompetenceImportation;

use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceImportationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceImportationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceImportationForm
    {
        /** @var CompetenceReferentielService $competenceReferentielService */
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);

        /** @var CompetenceImportationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceImportationHydrator::class);

        $form = new CompetenceImportationForm();
        $form->setCompetenceReferentielService($competenceReferentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}