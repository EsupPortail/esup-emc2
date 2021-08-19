<?php

namespace Metier\Service\FamilleProfessionnelle;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FamilleProfessionnelleServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FamilleProfessionnelleService
     */
    public function __invoke(ContainerInterface $container) : FamilleProfessionnelleService
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