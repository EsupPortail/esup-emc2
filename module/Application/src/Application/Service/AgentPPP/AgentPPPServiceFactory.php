<?php

namespace Application\Service\AgentPPP;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentPPPServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPPPService
     */
    public function __invoke(ContainerInterface $container) : AgentPPPService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new AgentPPPService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}