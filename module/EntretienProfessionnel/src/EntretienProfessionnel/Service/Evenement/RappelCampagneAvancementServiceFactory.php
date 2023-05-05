<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Provider\Event\EvenementProvider;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;

class RappelCampagneAvancementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelCampagneAvancementService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RappelCampagneAvancementService
    {
        /**
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var StructureService $structureService
         * @var TypeService $typeService
         */
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $structureService = $container->get(StructureService::class);
        $typeService = $container->get(TypeService::class);

        $service = new RappelCampagneAvancementService();
        $service->setAgentAutoriteService($agentAutoriteService);
        $service->setAgentSuperieurService($agentSuperieurService);
        $service->setCampagneService($campagneService);
        $service->setEtatEvenementService($etatService);
        $service->setNotificationService($notificationService);
        $service->setStructureService($structureService);

        $service->setType($typeService->findByCode(EvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT));
        return $service;
    }
}