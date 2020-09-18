<?php

namespace UnicaenDocument\Service\Contenu;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Macro\MacroService;
use UnicaenUtilisateur\Service\User\UserService;

class ContenuServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ContenuService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var MacroService $macroService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $macroService = $container->get(MacroService::class);
        $userService = $container->get(UserService::class);

        $service = new ContenuService();
        $service->setEntityManager($entityManager);
        $service->setMacroService($macroService);
        $service->setUserService($userService);
        return $service;
    }
}