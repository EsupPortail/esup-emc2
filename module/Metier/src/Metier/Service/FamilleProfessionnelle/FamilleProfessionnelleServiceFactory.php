<?php

namespace Metier\Service\FamilleProfessionnelle;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FamilleProfessionnelleServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FamilleProfessionnelleService $service */
        $service = new FamilleProfessionnelleService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}