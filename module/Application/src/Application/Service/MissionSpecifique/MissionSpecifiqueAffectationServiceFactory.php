<?php

namespace Application\Service\MissionSpecifique;

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
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);


        /** @var MissionSpecifiqueAffectationService $service */
        $service = new MissionSpecifiqueAffectationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}