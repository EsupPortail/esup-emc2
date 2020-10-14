<?php

namespace UnicaenEtat\Service\Etat;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class EtatServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new EtatService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}