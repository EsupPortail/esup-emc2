<?php

namespace Application\Service\FicheProfil;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FicheProfilServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $service = new FicheProfilService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setStructureService($structureService);
        return $service;
    }
}