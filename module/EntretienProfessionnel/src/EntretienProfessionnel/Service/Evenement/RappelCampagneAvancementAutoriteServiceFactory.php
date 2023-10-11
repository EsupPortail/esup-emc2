<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\AgentAutorite\AgentAutoriteService;
use EntretienProfessionnel\Provider\Event\EvenementProvider;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;

class RappelCampagneAvancementAutoriteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelCampagneAvancementAutoriteService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RappelCampagneAvancementAutoriteService
    {
        /**
         * @var AgentAutoriteService $agentAutoriteService
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var StructureService $structureService
         * @var TypeService $typeService
         */
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $structureService = $container->get(StructureService::class);
        $typeService = $container->get(TypeService::class);

        $service = new RappelCampagneAvancementAutoriteService();
        $service->setAgentAutoriteService($agentAutoriteService);
        $service->setCampagneService($campagneService);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setEtatEvenementService($etatService);
        $service->setNotificationService($notificationService);
        $service->setStructureService($structureService);

        $service->setType($typeService->findByCode(EvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_AUTORITE));
        return $service;
    }
}