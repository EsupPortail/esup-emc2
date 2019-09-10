<?php

namespace Autoform\Service\Formulaire;

use Autoform\Service\Champ\ChampService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class FormulaireReponseServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         * @var ChampService $champService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        $champService = $container->get(ChampService::class);

        /** @var FormulaireReponseService $service */
        $service = new FormulaireReponseService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setChampService($champService);
        return $service;
    }
}