<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;

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
         * @var EtatTypeService $etatTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $service = new CampagneService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setEtatTypeService($etatTypeService);
        return $service;
    }
}