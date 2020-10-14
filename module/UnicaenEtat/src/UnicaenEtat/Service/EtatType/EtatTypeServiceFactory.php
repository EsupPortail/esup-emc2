<?php

namespace UnicaenEtat\Service\EtatType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class EtatTypeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new EtatTypeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}