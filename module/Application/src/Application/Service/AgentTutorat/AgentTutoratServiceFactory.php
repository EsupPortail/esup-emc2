<?php

namespace Application\Service\AgentTutorat;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentTutoratServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratService
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new AgentTutoratService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}