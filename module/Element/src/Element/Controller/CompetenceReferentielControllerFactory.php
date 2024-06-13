<?php

namespace Element\Controller;

use Element\Entity\Db\Competence;
use Element\Form\CompetenceReferentiel\CompetenceReferentielForm;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
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
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceReferentielForm $competenceReferentielForm
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceReferentielForm = $container->get('FormElementManager')->get(CompetenceReferentielForm::class);

        $controller = new CompetenceReferentielController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCompetenceReferentielForm($competenceReferentielForm);
        return $controller;
    }
}