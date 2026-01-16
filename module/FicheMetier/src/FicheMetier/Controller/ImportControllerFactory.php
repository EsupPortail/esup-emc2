<?php

namespace FicheMetier\Controller;

use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Carriere\Service\Niveau\NiveauService;
use Carriere\Service\NiveauFonction\NiveauFonctionService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceType\CompetenceTypeService;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionService;
use FicheMetier\Service\Import\ImportService;
use FicheMetier\Service\MissionActivite\MissionActiviteService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FicheMetier\Service\TendanceElement\TendanceElementService;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenParametre\Service\Parametre\ParametreService;

class ImportControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImportController
    {
        /**
         * @var CodeFonctionService $codeFonctionService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceTypeService $competenceTypeService
         * @var CategorieService $categorieService
         * @var CorrespondanceService $correspondanceService
         * @var EtatInstanceService $etatInstanceService
         * @var FamilleProfessionnelleService $familleProfessionnelService
         * @var FicheMetierService $ficheMetierService
         * @var FicheMetierMissionService $ficheMetierMissionService
         * @var ImportService $importService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var MissionActiviteService $missionActiviteService
         * @var NiveauService $niveauService
         * @var NiveauFonctionService $niveauFonctionService
         * @var ParametreService $parametreService
         * @var ReferentielService $referentielService
         * @var TendanceElementService $tendanceElementService
         * @var TendanceTypeService $tendanceTypeService
         */
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $familleProfessionnelService = $container->get(FamilleProfessionnelleService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheMetierMissionService = $container->get(FicheMetierMissionService::class);
        $importService = $container->get(ImportService::class);
        $missionActiviteService = $container->get(MissionActiviteService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $niveauService = $container->get(NiveauService::class);
        $niveauFonctionService = $container->get(NiveauFonctionService::class);
        $parametreService = $container->get(ParametreService::class);
        $referentielService = $container->get(ReferentielService::class);
        $tendanceElementService = $container->get(TendanceElementService::class);
        $tendanceTypeService = $container->get(TendanceTypeService::class);

        /**
         * @var FicheMetierImportationForm $ficheMetierImportationForm
         */
        $ficheMetierImportationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);

        $controller = new ImportController();
        $controller->setCodeFonctionService($codeFonctionService);
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFamilleProfessionnelleService($familleProfessionnelService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheMetierMissionService($ficheMetierMissionService);
        $controller->setImportService($importService);
        $controller->setMissionActiviteService($missionActiviteService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setNiveauService($niveauService);
        $controller->setNiveauFonctionService($niveauFonctionService);
        $controller->setParametreService($parametreService);
        $controller->setReferentielService($referentielService);
        $controller->setTendanceElementService($tendanceElementService);
        $controller->setTendanceTypeService($tendanceTypeService);
        $controller->setFicheMetierImportationForm($ficheMetierImportationForm);

        return $controller;
    }

}