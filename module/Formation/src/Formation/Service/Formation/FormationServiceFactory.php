<?php

namespace Formation\Service\Formation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationService
     */
    public function __invoke(ContainerInterface $container) : FormationService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationService $service */
        $service = new FormationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}
