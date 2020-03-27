<?php

namespace Autoform\Service\Categorie;

use Autoform\Service\Champ\ChampService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class CategorieServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var ChampService $champService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $champService = $container->get(ChampService::class);
        $userService = $container->get(UserService::class);

        /** @var CategorieService $service */
        $service = new CategorieService();
        $service->setEntityManager($entityManager);
        $service->setChampService($champService);
        $service->setUserService($userService);
        return $service;
    }
}