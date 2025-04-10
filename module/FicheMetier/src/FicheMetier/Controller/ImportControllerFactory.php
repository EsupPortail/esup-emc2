<?php

namespace FicheMetier\Controller;

use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use FicheReferentiel\Form\Importation\ImportationForm;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Metier\Service\Metier\MetierService;
use Metier\Service\Reference\ReferenceService;
use Metier\Service\Reference\ReferenceServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImportController
    {
        /**
         * @var CategorieService $categorieService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CategorieService $categorieService
         * @var CorrespondanceService $correspondanceService
         * @var DomaineService $domaineService
         * @var FamilleProfessionnelleService $familleProfessionnelService
         * @var MetierService $metierService
         * @var ReferenceServiceAwareTrait $referenceService
         * @var ReferentielService $referentielService
         */
        $categorieService = $container->get(CategorieService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $domaineService = $container->get(DomaineService::class);
        $familleProfessionnelService = $container->get(FamilleProfessionnelleService::class);
        $metierService = $container->get(MetierService::class);
        $referenceService = $container->get(ReferenceService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var ImportationForm $importationForm
         */
        $importationForm = $container->get('FormElementManager')->get(ImportationForm::class);

        $controller = new ImportController();
        $controller->setCategorieService($categorieService);
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleProfessionnelService);
        $controller->setMetierService($metierService);
        $controller->setReferenceService($referenceService);
        $controller->setReferentielService($referentielService);
        $controller->setImportationForm($importationForm);

        return $controller;
    }

}