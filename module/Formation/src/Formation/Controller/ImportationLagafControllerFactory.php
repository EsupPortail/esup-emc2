<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\HasFormationCollection\HasFormationCollectionService;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Stagiaire\StagiaireService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;

class ImportationLagafControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return ImportationLagafController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImportationLagafController
    {
        /**
         * @var EtatInstanceService $etatInstanceService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var FormationInstanceService $formationInstanceService
         * @var SeanceService $seanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceFraisService $formationInstanceFraisService
         * @var PresenceService $presenceService
         * @var HasFormationCollectionService $hasFormationCollectionService
         * @var StagiaireService $stagiaireService
         */
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $seanceService = $container->get(SeanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);
        $presenceService = $container->get(PresenceService::class);
        $hasFormationCollectionService = $container->get(HasFormationCollectionService::class);
        $stagiaireService = $container->get(StagiaireService::class);

        $controller = new ImportationLagafController();
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setSeanceService($seanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceFraisService($formationInstanceFraisService);
        $controller->setPresenceService($presenceService);
        $controller->setHasFormationCollectionService($hasFormationCollectionService);
        $controller->setStagiaireService($stagiaireService);
        $controller->sourceLagaf = HasSourceInterface::SOURCE_LAGAF;
        return $controller;
    }
}