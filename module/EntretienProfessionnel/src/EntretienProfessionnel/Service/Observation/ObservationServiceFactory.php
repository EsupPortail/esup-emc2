<?php

namespace EntretienProfessionnel\Service\Observation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ObservationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationService
     */
    public function __invoke(ContainerInterface $container) : ObservationService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new ObservationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}