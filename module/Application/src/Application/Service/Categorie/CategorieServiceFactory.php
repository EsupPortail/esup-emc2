<?php

namespace Application\Service\Categorie;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class CategorieServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieService
     */
    public function __invoke(ContainerInterface $container) : CategorieService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var CategorieService $service */
        $service = new CategorieService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}