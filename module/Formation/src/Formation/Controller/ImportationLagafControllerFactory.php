<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Formation\Entity\Db\InscriptionFrais;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\HasFormationCollection\HasFormationCollectionService;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Stagiaire\StagiaireService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenUtilisateur\Service\User\UserService;

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
         * @var PresenceService $presenceService
         * @var HasFormationCollectionService $hasFormationCollectionService
         * @var StagiaireService $stagiaireService
         * @var UserService $userService
         */
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $seanceService = $container->get(SeanceService::class);
        $formationInstanceInscritService = $container->get(InscriptionService::class);
        $formationInstanceFraisService = $container->get(InscriptionFrais::class);
        $presenceService = $container->get(PresenceService::class);
        $hasFormationCollectionService = $container->get(HasFormationCollectionService::class);
        $stagiaireService = $container->get(StagiaireService::class);
        $userService = $container->get(UserService::class);

        $controller = new ImportationLagafController();
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setSeanceService($seanceService);
        $controller->setInscriptionService($formationInstanceInscritService);
        $controller->setInscriptionFraisService($formationInstanceFraisService);
        $controller->setPresenceService($presenceService);
        $controller->setHasFormationCollectionService($hasFormationCollectionService);
        $controller->setStagiaireService($stagiaireService);
        $controller->setUserService($userService);
        $controller->sourceLagaf = HasSourceInterface::SOURCE_LAGAF;
        return $controller;
    }
}