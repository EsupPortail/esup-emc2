<?php

namespace Formation\Service\FormationInstance;

use Doctrine\ORM\EntityManager;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\Evenement\RappelAgentAvantFormationService;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationInstanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationInstanceService
    {
        /**
         * @var EntityManager $entityManager
         * @var AbonnementService $abonnementService
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var RappelAgentAvantFormationService $rappelAgentAvantForamtionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $abonnementService = $container->get(AbonnementService::class);
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $rappelAgentAvantForamtionService = $container->get(RappelAgentAvantFormationService::class);

        /**
         * @var FormationInstanceService $service
         */
        $service = new FormationInstanceService();
        $service->setEntityManager($entityManager);
        $service->setAbonnementService($abonnementService);
        $service->setEtatService($etatService);
        $service->setNotificationService($notificationService);
        $service->setParametreService($parametreService);
        $service->setRappelAgentAvantFormationService($rappelAgentAvantForamtionService);
        return $service;
    }
}