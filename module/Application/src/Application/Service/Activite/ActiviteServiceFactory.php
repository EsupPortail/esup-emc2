<?php

namespace Application\Service\Activite;

use Utilisateur\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ActiviteServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ActiviteService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator) {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var ActiviteService $service */
        $service = new ActiviteService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}