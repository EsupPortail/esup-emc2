<?php

namespace FicheMetier\Service\FicheMetier;

use Application\Service\Activite\ActiviteService;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Application\Service\Configuration\ConfigurationService;
use Doctrine\ORM\EntityManager;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
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
         * @var EtatService $etatService
         *
         * @var ActiviteService $activiteService
         * @var ActiviteDescriptionService $activiteDescriptionService
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
        $etatService = $container->get(EtatService::class);
        $renduService = $container->get(RenduService::class);

        $activiteService = $container->get(ActiviteService::class);
        $activiteDescriptionService = $container->get(ActiviteDescriptionService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $metierService = $container->get(MetierService::class);

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setConfigurationService($configurationService);
        $service->setDomaineService($domaineService);
        $service->setEtatService($etatService);
        $service->setRenduService($renduService);

        $service->setActiviteService($activiteService);
        $service->setActiviteDescriptionService($activiteDescriptionService);
        $service->setHasApplicationCollectionService($hasApplicationCollectionService);
        $service->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $service->setMetierService($metierService);

        return $service;
    }
}