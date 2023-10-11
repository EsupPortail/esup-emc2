<?php

namespace Element\Controller;

use Element\Form\CompetenceReferentiel\CompetenceReferentielForm;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceReferentielControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceReferentielController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceReferentielController
    {
        /**
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceReferentielForm $competenceReferentielForm
         */
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceReferentielForm = $container->get('FormElementManager')->get(CompetenceReferentielForm::class);

        $controller = new CompetenceReferentielController();
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCompetenceReferentielForm($competenceReferentielForm);
        return $controller;
    }
}