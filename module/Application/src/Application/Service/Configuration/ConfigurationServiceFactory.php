<?php

namespace Application\Service\Configuration;

use Application\Service\FicheMetier\FicheMetierService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

;

class ConfigurationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FicheMetierService $ficheMetierService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ficheMetierService = $container->get(FicheMetierService::class);
        $userService = $container->get(UserService::class);

        /** @var ConfigurationService $service */
        $service = new ConfigurationService();
        $service->setEntityManager($entityManager);
        $service->setFicheMetierService($ficheMetierService);
        $service->setUserService($userService);
        return $service;
    }
}