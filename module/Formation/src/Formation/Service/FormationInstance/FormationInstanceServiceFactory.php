<?php

namespace Formation\Service\FormationInstance;

use Doctrine\ORM\EntityManager;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationInstanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceService
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatInstanceService $etatInstanceService
         * @var EtatTypeService $etatTypeService
         * @var AbonnementService $abonnementService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var RappelAgentAvantFormationService $rappelAgentAvantForamtionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $abonnementService = $container->get(AbonnementService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $rappelAgentAvantForamtionService = $container->get(RappelAgentAvantFormationService::class);

        /**
         * @var FormationInstanceService $service
         */
        $service = new FormationInstanceService();
        $service->setObjectManager($entityManager);
        $service->setAbonnementService($abonnementService);
        $service->setEtatInstanceService($etatInstanceService);
        $service->setEtatTypeService($etatTypeService);
        $service->setNotificationService($notificationService);
        $service->setParametreService($parametreService);
        $service->setRappelAgentAvantFormationService($rappelAgentAvantForamtionService);
        return $service;
    }
}