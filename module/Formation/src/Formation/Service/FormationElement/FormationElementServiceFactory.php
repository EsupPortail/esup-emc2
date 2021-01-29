<?php

namespace Formation\Service\FormationElement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationElementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationElementService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new FormationElementService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}