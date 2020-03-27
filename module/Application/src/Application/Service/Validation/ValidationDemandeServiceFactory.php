<?php

namespace Application\Service\Validation;

use Application\Service\FicheMetier\FicheMetierService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class ValidationDemandeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FicheMetierService $ficheMetierService
         * @var UserService $userService
         * @var ValidationTypeService $validationTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ficheMetierService = $container->get(FicheMetierService::class);
        $userService = $container->get(UserService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        /** @var ValidationDemandeService $service */
        $service = new ValidationDemandeService();
        $service->setEntityManager($entityManager);
        $service->setFicheMetierService($ficheMetierService);
        $service->setUserService($userService);
        $service->setValidationTypeService($validationTypeService);

        return $service;
    }
}