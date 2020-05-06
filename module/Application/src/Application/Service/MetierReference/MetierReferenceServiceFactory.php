<?php

namespace Application\Service\MetierReference;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class MetierReferenceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierReferenceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var MetierReferenceService $service */
        $service = new MetierReferenceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}