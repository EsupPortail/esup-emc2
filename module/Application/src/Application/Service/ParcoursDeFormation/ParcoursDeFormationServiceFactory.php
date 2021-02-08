<?php

namespace Application\Service\ParcoursDeFormation;

use Application\Service\Categorie\CategorieService;
use Application\Service\Metier\MetierService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use UnicaenUtilisateur\Service\User\UserService;

class ParcoursDeFormationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var CategorieService $categorieService
         * @var DomaineService $domaineService
         * @var MetierService $metierService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categorieService = $container->get(CategorieService::class);
        $domaineService = $container->get(DomaineService::class);
        $metierService = $container->get(MetierService::class);
        $userService = $container->get(UserService::class);

        /** @var ParcoursDeFormationService $service */
        $service = new ParcoursDeFormationService();
        $service->setEntityManager($entityManager);
        $service->setCategorieService($categorieService);
        $service->setDomaineService($domaineService);
        $service->setMetierService($metierService);
        $service->setUserService($userService);
        return $service;
    }
}