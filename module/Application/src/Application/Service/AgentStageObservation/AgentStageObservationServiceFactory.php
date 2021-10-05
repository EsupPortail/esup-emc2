<?php

namespace Application\Service\AgentStageObservation;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentStageObservationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationService
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new AgentStageObservationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}