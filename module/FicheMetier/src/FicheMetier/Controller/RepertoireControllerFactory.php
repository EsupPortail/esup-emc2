<?php

namespace FicheMetier\Controller;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceType\CompetenceTypeService;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\Repertoire\RepertoireService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RepertoireControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RepertoireController
    {
        /**
         * @var CompetenceService $competenceService
         * @var FicheMetierService $ficheMetierService
         * @var RepertoireService $repertoireService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceService $competenceService
         * @var FicheMetierImportationForm $ficheMetierImportationForm
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $repertoireService = $container->get(RepertoireService::class);
        $ficheMetierImportationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);

        $controller = new RepertoireController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setRepertoireService($repertoireService);
        $controller->setFicheMetierImportationForm($ficheMetierImportationForm);
        return $controller;
    }

}