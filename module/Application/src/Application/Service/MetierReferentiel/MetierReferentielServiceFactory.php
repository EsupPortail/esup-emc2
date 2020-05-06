<?php

namespace Application\Service\MetierReferentiel;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class MetierReferentielServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierReferentielService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var MetierReferentielService $service */
        $service = new MetierReferentielService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}