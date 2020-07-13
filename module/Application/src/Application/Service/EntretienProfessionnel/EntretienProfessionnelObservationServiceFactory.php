<?php

namespace Application\Service\EntretienProfessionnel;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class EntretienProfessionnelObservationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelObservationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /**
         * @var EntretienProfessionnelObservationService $service
         */
        $service = new EntretienProfessionnelObservationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}