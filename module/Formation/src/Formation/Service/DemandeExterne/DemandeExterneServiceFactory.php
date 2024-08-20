<?php

namespace Formation\Service\DemandeExterne;

use Doctrine\ORM\EntityManager;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class DemandeExterneServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DemandeExterneService
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatInstanceService $etatInstanceService
         * @var EtatTypeService $etatTypeService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var SessionService $sessionService
         * @var InscriptionService $inscriptionService
         * @var SeanceService $seanceService
         * @var PresenceService $presenceService
         * @var StructureService $structureService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $sessionService = $container->get(SessionService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $presenceService = $container->get(PresenceService::class);
        $seanceService = $container->get(SeanceService::class);
        $structureService = $container->get(StructureService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        $service = new DemandeExterneService();
        $service->setObjectManager($entityManager);
        $service->setEtatInstanceService($etatInstanceService);
        $service->setEtatTypeService($etatTypeService);
        $service->setFormationService($formationService);
        $service->setSessionService($sessionService);
        $service->setFormationGroupeService($formationGroupeService);
        $service->setInscriptionService($inscriptionService);
        $service->setPresenceService($presenceService);
        $service->setSeanceService($seanceService);
        $service->setStructureService($structureService);
        $service->setValidationInstanceService($validationInstanceService);
        $service->setValidationTypeService($validationTypeService);

        return $service;
    }
}