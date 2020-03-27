<?php

namespace Application\Service\Validation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class ValidationServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ValidationService $validationService */
        $validationService = new ValidationService();
        $validationService->setEntityManager($entityManager);
        $validationService->setUserService($userService);
        return $validationService;
    }
}