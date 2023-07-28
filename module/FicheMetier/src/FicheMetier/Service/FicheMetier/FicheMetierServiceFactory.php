<?php

namespace FicheMetier\Service\FicheMetier;

use Application\Service\Configuration\ConfigurationService;
use Doctrine\ORM\EntityManager;
use Element\Form\SelectionApplication\SelectionApplicationHydrator;
use Element\Form\SelectionCompetence\SelectionCompetenceHydrator;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;
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
         * @var ConfigurationService $configurationService
         * @var DomaineService $domaineService
         * @var EtatTypeService $etatTypeService
         * @var MissionPrincipaleService $missionPrincipaleService
         *
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var MetierService $metierService
         * @var RenduService $renduService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $domaineService = $container->get(DomaineService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $renduService = $container->get(RenduService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $metierService = $container->get(MetierService::class);

        /**
         * @var SelectionApplicationHydrator $selectionApplicationHydrator
         * @var SelectionCompetenceHydrator $selectionCompetenceHydrator
         */
        $selectionApplicationHydrator = $container->get('HydratorManager')->get(SelectionApplicationHydrator::class);
        $selectionCompetenceHydrator = $container->get('HydratorManager')->get(SelectionCompetenceHydrator::class);

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setConfigurationService($configurationService);
        $service->setDomaineService($domaineService);
        $service->setEtatTypeService($etatTypeService);
        $service->setMissionPrincipaleService($missionPrincipaleService);
        $service->setRenduService($renduService);

        $service->setHasApplicationCollectionService($hasApplicationCollectionService);
        $service->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $service->setMetierService($metierService);

        $service->setSelectionApplicationHydrator($selectionApplicationHydrator);
        $service->setSelectionCompetenceHydrator($selectionCompetenceHydrator);

        return $service;
    }
}