<?php

namespace Autoform\Service\Categorie;

use Application\Service\User\UserService;
use Autoform\Service\Champ\ChampService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class CategorieServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var ChampService $champService
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $champService = $serviceLocator->get(ChampService::class);
        $userService = $serviceLocator->get(UserService::class);

        /** @var CategorieService $service */
        $service = new CategorieService();
        $service->setEntityManager($entityManager);
        $service->setChampService($champService);
        $service->setUserService($userService);
        return $service;
    }
}