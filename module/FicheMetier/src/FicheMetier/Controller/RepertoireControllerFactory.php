<?php

namespace FicheMetier\Controller;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceType\CompetenceTypeService;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\Repertoire\RepertoireService;
use FicheReferentiel\Service\FicheReferentiel\FicheReferentielService;
use Metier\Service\Metier\MetierService;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RepertoireControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RepertoireController
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var FicheMetierService $ficheMetierService
         * @var FicheReferentielService $ficheReferentielService
         * @var MetierService $metierService
         * @var ReferentielService $referentielService
         * @var RepertoireService $repertoireService
         * @var CompetenceTypeService $competenceTypeService
         * @var FicheMetierImportationForm $ficheMetierImportationForm
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheReferentielService = $container->get(FicheReferentielService::class);
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(ReferentielService::class);
        $repertoireService = $container->get(RepertoireService::class);
        $ficheMetierImportationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);

        $controller = new RepertoireController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheReferentielService($ficheReferentielService);
        $controller->setMetierService($metierService);
        $controller->setReferentielService($referentielService);
        $controller->setRepertoireService($repertoireService);
        $controller->setFicheMetierImportationForm($ficheMetierImportationForm);
        return $controller;
    }

}