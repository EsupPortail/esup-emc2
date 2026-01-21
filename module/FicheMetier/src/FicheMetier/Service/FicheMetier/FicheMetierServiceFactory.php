<?php

namespace FicheMetier\Service\FicheMetier;

use Application\Service\Configuration\ConfigurationService;
use Application\Service\Macro\MacroService;
use Carriere\Service\NiveauFonction\NiveauFonctionService;
use Doctrine\ORM\EntityManager;
use Element\Form\SelectionApplication\SelectionApplicationHydrator;
use Element\Form\SelectionCompetence\SelectionCompetenceHydrator;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Interop\Container\ContainerInterface;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;

class FicheMetierServiceFactory {
    /**
     * @param ContainerInterface $container
     * @return FicheMetierService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FicheMetierService
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CodeFonctionService $codeFonctionService
         * @var ConfigurationService $configurationService
         * @var EtatInstanceService $etatInstanceService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var MacroService $macroService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var NiveauFonctionService $niveauFonctionService
         * @var ParametreService $parametreService
         * @var ReferentielService $referentielService
         *
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var RenduService $renduService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $macroService = $container->get(MacroService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $niveauFonctionService = $container->get(NiveauFonctionService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var SelectionApplicationHydrator $selectionApplicationHydrator
         * @var SelectionCompetenceHydrator $selectionCompetenceHydrator
         */
        $selectionApplicationHydrator = $container->get('HydratorManager')->get(SelectionApplicationHydrator::class);
        $selectionCompetenceHydrator = $container->get('HydratorManager')->get(SelectionCompetenceHydrator::class);

        $service = new FicheMetierService();
        $service->setObjectManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);
        $service->setCodeFonctionService($codeFonctionService);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setConfigurationService($configurationService);
        $service->setEtatInstanceService($etatInstanceService);
        $service->setFamilleProfessionnelleService($familleProfessionnelleService);
        $service->setMacroService($macroService);
        $service->setMissionPrincipaleService($missionPrincipaleService);
        $service->setNiveauFonctionService($niveauFonctionService);
        $service->setParametreService($parametreService);
        $service->setReferentielService($referentielService);
        $service->setRenduService($renduService);

        $service->setHasApplicationCollectionService($hasApplicationCollectionService);
        $service->setHasCompetenceCollectionService($hasCompetenceCollectionService);

        $service->setSelectionApplicationHydrator($selectionApplicationHydrator);
        $service->setSelectionCompetenceHydrator($selectionCompetenceHydrator);

        return $service;
    }
}