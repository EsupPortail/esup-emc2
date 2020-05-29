<?php

namespace Application\Service\EntretienProfessionnel;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class EntretienProfessionnelCampagneServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /**
         * @var EntretienProfessionnelCampagneService $service
         */
        $service = new EntretienProfessionnelCampagneService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}