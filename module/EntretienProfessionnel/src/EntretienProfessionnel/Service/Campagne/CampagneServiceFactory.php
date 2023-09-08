<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
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
         * @var EtatService $etatService
		 * @var ParametreService $parametreService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $etatService = $container->get(EtatService::class);
		$parametreService = $container->get(ParametreService::class);

        $service = new CampagneService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setEtatService($etatService);
		$service->setParametreService($parametreService);
        return $service;
    }
}
