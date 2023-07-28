<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatService;

class CampagneServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CampagneService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatService $etatService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $etatService = $container->get(EtatService::class);

        $service = new CampagneService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setEtatService($etatService);
        return $service;
    }
}