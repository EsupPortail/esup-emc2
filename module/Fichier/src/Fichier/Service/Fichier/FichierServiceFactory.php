<?php

namespace Fichier\Service\Fichier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;
use Laminas\ServiceManager\ServiceLocatorInterface;

class FichierServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        $path = $container->get('Config')['unicaen-fichier']['upload-path'];

        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FichierService $service */
        $service = new FichierService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setPath($path);
        return $service;
    }
}