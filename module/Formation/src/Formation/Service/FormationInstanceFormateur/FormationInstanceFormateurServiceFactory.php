<?php

namespace Formation\Service\FormationInstanceFormateur;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceFormateurServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFormateurService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationInstanceFormateurService $service */
        $service = new FormationInstanceFormateurService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}