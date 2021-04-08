<?php

namespace Application\Service\MaitriseNiveau;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class MaitriseNiveauServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MaitriseNiveauService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new MaitriseNiveauService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}