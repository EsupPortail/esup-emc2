<?php

namespace Formation\Service\FormationInstanceInscrit;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritService
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

        /** @var FormationInstanceInscritService $service */
        $service = new FormationInstanceInscritService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}