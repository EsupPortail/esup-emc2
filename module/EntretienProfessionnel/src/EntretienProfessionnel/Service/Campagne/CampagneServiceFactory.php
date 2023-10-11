<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

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
         * @var ParametreService $parametreService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $parametreService = $container->get(ParametreService::class);

        $service = new CampagneService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setEtatTypeService($etatTypeService);
        $service->setParametreService($parametreService);
        return $service;
    }
}