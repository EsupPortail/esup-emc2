<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\AgentSuperieur\AgentSuperieurService;
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

class RappelCampagneAvancementSuperieurServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelCampagneAvancementSuperieurService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RappelCampagneAvancementSuperieurService
    {
        /**
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var StructureService $structureService
         * @var TypeService $typeService
         */
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $structureService = $container->get(StructureService::class);
        $typeService = $container->get(TypeService::class);

        $service = new RappelCampagneAvancementSuperieurService();
        $service->setAgentSuperieurService($agentSuperieurService);
        $service->setCampagneService($campagneService);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setEtatEvenementService($etatService);
        $service->setNotificationService($notificationService);
        $service->setStructureService($structureService);

        $service->setType($typeService->findByCode(EvenementProvider::RAPPEL_CAMPAGNE_AVANCEMENT_SUPERIEUR));
        return $service;
    }
}