<?php

namespace UnicaenDocument\Service\Macro;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class MacroServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MacroService
     */
    public function __invoke(ContainerInterface $container) : MacroService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new MacroService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}