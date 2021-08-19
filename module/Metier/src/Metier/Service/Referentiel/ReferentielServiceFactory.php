<?php

namespace Metier\Service\Referentiel;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ReferentielServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielService
     */
    public function __invoke(ContainerInterface $container) : ReferentielService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ReferentielService $service */
        $service = new ReferentielService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}