<?php

namespace Fichier\Service\Fichier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FichierServiceFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FichierService
    {
        $path = $container->get('Config')['unicaen-fichier']['upload-path'];

        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new FichierService();
        $service->setObjectManager($entityManager);
        $service->setUserService($userService);
        $service->setPath($path);
        return $service;
    }
}