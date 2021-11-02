<?php

namespace Application\Service\AgentMissionSpecifique;

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
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new AgentMissionSpecifiqueService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}