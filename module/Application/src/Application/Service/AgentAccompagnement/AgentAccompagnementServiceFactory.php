<?php

namespace Application\Service\AgentAccompagnement;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentAccompagnementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementService
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new AgentAccompagnementService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}