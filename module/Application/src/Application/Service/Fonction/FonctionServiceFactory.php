<?php

namespace Application\Service\Fonction;

use Doctrine\ORM\EntityManager;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class FonctionServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         * @var \Octopus\Service\Fonction\FonctionService $fonctionService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);
        $fonctionService = $serviceLocator->get(\Octopus\Service\Fonction\FonctionService::class);

        /** @var FonctionService $service */
        $service = new FonctionService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setFonctionService($fonctionService);
        return $service;

    }
}