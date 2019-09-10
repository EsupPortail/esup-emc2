<?php

namespace Autoform\Service\Formulaire;

use Autoform\Service\Categorie\CategorieService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class FormulaireServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var CategorieService $categorieService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categorieService = $container->get(CategorieService::class);
        $userService = $container->get(UserService::class);

        /** @var FormulaireService $service */
        $service = new FormulaireService();
        $service->setEntityManager($entityManager);
        $service->setCategorieService($categorieService);
        $service->setUserService($userService);
        return $service;
    }
}