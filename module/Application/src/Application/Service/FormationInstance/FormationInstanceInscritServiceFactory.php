<?php

namespace Application\Service\FormationInstance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritServiceFactory {

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

        /** @var FormationInstanceInscritService $service */
        $service = new FormationInstanceInscritService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}