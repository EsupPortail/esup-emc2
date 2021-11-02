<?php

namespace Application\Service\AgentMissionSpecifique;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentMissionSpecifiqueServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentMissionSpecifiqueService
     */
    public function __invoke(ContainerInterface $container) : AgentMissionSpecifiqueService
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $service = new AgentMissionSpecifiqueService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}