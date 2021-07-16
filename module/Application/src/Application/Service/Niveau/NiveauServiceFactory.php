<?php

namespace Application\Service\Niveau;

use Application\Service\Categorie\CategorieService;
use Metier\Service\Metier\MetierService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use UnicaenUtilisateur\Service\User\UserService;

class NiveauServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var NiveauService $service */
        $service = new NiveauService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}