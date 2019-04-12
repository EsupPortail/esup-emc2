<?php

namespace Autoform\Service\Formulaire;

use Utilisateur\Service\User\UserService;
use Autoform\Service\Categorie\CategorieService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormulaireServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var CategorieService $categorieService
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $categorieService = $serviceLocator->get(CategorieService::class);
        $userService = $serviceLocator->get(UserService::class);

        /** @var FormulaireService $service */
        $service = new FormulaireService();
        $service->setEntityManager($entityManager);
        $service->setCategorieService($categorieService);
        $service->setUserService($userService);
        return $service;
    }
}