<?php

namespace Application\Service\CompetenceMaitrise;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class CompetenceMaitriseServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceMaitriseService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new CompetenceMaitriseService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}