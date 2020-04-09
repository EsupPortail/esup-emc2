<?php

namespace Application\Service\MissionSpecifique;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class MissionSpecifiqueAffectationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueAffectationService
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


        /** @var MissionSpecifiqueAffectationService $service */
        $service = new MissionSpecifiqueAffectationService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}