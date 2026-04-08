<?php

namespace EntretienProfessionnel\Service\CampagneProgressionStructure;

use Agent\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureAgentForce\StructureAgentForceService;

class CampagneProgressionStructureServiceFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneProgressionStructureService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);

        $service = new CampagneProgressionStructureService();
        $service->setObjectManager($entityManager);
        $service->setAgentService($agentService);
        $service->setCampagneService($campagneService);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setStructureService($structureService);
        $service->setStructureAgentForceService($structureAgentForceService);

        return $service;
    }
}
