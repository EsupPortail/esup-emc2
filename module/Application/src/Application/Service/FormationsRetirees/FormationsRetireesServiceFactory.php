<?php

namespace Application\Service\FormationsRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class FormationsRetireesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationsRetireesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationsRetireesService $service */
        $service = new FormationsRetireesService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}