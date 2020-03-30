<?php

namespace Application\Service\Expertise;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ExpertiseServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ExpertiseService $service */
        $service = new ExpertiseService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}
