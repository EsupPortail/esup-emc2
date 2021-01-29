<?php

namespace Formation\Service\HasFormationCollection;

use Formation\Service\Formation\FormationService;
use Formation\Service\FormationElement\FormationElementService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class HasFormationCollectionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return HasFormationCollectionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationService $applicationService
         * @var FormationElementService $applicationElementService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(FormationService::class);
        $applicationElementService = $container->get(FormationElementService::class);
        $userService = $container->get(UserService::class);

        $service = new HasFormationCollectionService();
        $service->setEntityManager($entityManager);
        $service->setFormationService($applicationService);
        $service->setFormationElementService($applicationElementService);
        $service->setUserService($userService);
        return $service;
    }
}