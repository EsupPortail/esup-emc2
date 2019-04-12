<?php

namespace Application\Service\EntretienProfessionnel;

use Utilisateur\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntretienProfessionnelServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /**
         * @var EntretienProfessionnelService $service
         */
        $service = new EntretienProfessionnelService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}