<?php

namespace UnicaenGlossaire\Service\Definition;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class DefinitionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DefinitionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new DefinitionService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}