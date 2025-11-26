<?php

namespace FicheMetier\Controller;

use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceType\CompetenceTypeService;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionService;
use FicheMetier\Service\Import\ImportService;
use FicheMetier\Service\MissionActivite\MissionActiviteService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Metier\Service\Metier\MetierService;
use Metier\Service\Reference\ReferenceService;
use Metier\Service\Reference\ReferenceServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;

class ImportControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImportController
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceTypeService $competenceTypeService
         * @var CategorieService $categorieService
         * @var CorrespondanceService $correspondanceService
         * @var EtatInstanceService $etatInstanceService
         * @var FamilleProfessionnelleService $familleProfessionnelService
         * @var FicheMetierService $ficheMetierService
         * @var FicheMetierMissionService $ficheMetierMissionService
         * @var ImportService $importService
         * @var MetierService $metierService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var MissionActiviteService $missionActiviteService
         * @var ReferenceServiceAwareTrait $referenceService
         * @var ReferentielService $referentielService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $familleProfessionnelService = $container->get(FamilleProfessionnelleService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheMetierMissionService = $container->get(FicheMetierMissionService::class);
        $importService = $container->get(ImportService::class);
        $metierService = $container->get(MetierService::class);
        $missionActiviteService = $container->get(MissionActiviteService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $referenceService = $container->get(ReferenceService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var FicheMetierImportationForm $ficheMetierImportationForm
         */
        $ficheMetierImportationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);

        $controller = new ImportController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFamilleProfessionnelleService($familleProfessionnelService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheMetierMissionService($ficheMetierMissionService);
        $controller->setImportService($importService);
        $controller->setMetierService($metierService);
        $controller->setMissionActiviteService($missionActiviteService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setReferenceService($referenceService);
        $controller->setReferentielService($referentielService);
        $controller->setFicheMetierImportationForm($ficheMetierImportationForm);

        return $controller;
    }

}