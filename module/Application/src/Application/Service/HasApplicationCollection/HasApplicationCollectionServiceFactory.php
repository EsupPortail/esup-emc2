<?php

namespace Application\Service\HasApplicationCollection;

use Application\Service\Application\ApplicationService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class HasApplicationCollectionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return HasApplicationCollectionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $userService = $container->get(UserService::class);

        $service = new HasApplicationCollectionService();
        $service->setEntityManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);
        $service->setUserService($userService);
        return $service;
    }
}