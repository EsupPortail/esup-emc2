<?php

namespace EntretienProfessionnel\Service\Evenement;

use Doctrine\ORM\EntityManager;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenUtilisateur\Service\User\UserService;

class RappelPasObservationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelPasObservationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RappelPasObservationService
    {
        /**
         * @var EntityManager $entityManager
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var EtatService $etatService
         * @var EtatInstanceService $etatInstanceService
         * @var NotificationService $notificationService
         * @var TypeService $typeService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $etatService = $container->get(EtatService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $notificationService = $container->get(NotificationService::class);
        $typeService = $container->get(TypeService::class);
        $userService = $container->get(UserService::class);

        $service = new RappelPasObservationService();

        $service->setObjectManager($entityManager);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setEtatEvenementService($etatService);
        $service->setEtatInstanceService($etatInstanceService);
        $service->setNotificationService($notificationService);
        $service->setTypeService($typeService);
        $service->setUserService($userService);
        return $service;
    }
}