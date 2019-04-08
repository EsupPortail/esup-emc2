<?php

namespace Autoform\Service\Formulaire;

use Application\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormulaireInstanceServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var FormulaireInstanceService $service */
        $service = new FormulaireInstanceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}