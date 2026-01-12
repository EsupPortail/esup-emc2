<?php

namespace Element\Controller;

use Element\Form\CompetenceSynonyme\CompetenceSynonymeForm;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceSynonyme\CompetenceSynonymeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceSynonymeControllerFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceSynonymeController
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceSynonymeService $competenceSynonymeService
         * @var CompetenceSynonymeForm $competenceSynonymeForm
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceSynonymeService = $container->get(CompetenceSynonymeService::class);
        $competenceSynonymeForm = $container->get('FormElementManager')->get(CompetenceSynonymeForm::class);

        $controller = new CompetenceSynonymeController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceSynonymeService($competenceSynonymeService);
        $controller->setCompetenceSynonymeForm($competenceSynonymeForm);
        return $controller;

    }
}
