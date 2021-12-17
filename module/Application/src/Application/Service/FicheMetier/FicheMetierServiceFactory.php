<?php

namespace Application\Service\FicheMetier;

use Application\Service\Application\ApplicationService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Doctrine\ORM\EntityManager;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class FicheMetierServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var EtatService $etatService
         * @var FormationService $formationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $etatService = $container->get(EtatService::class);
        $formationService = $container->get(FormationService::class);

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setEtatService($etatService);
        $service->setFormationService($formationService);

        return $service;
    }
}