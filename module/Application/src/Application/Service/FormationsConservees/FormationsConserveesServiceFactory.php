<?php

namespace Application\Service\FormationsConservees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class FormationsConserveesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationsConserveesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationsConserveesService $service */
        $service = new FormationsConserveesService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}