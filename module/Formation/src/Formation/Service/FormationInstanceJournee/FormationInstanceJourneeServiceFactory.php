<?php

namespace Formation\Service\FormationInstanceJournee;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceJourneeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceJourneeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationInstanceJourneeService $service */
        $service = new FormationInstanceJourneeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}