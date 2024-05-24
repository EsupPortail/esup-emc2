<?php

namespace Structure\Service\Structure;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class StructureServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): StructureService
    {
        /**
         * @var EntityManager $entityManager
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        $service = new StructureService();
        $service->setObjectManager($entityManager);
        $service->setParametreService($parametreService);
        $service->setUserService($userService);

        return $service;
    }
}