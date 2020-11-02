<?php

namespace Formation\Service\FormationInstanceFrais;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceFraisServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationInstanceFraisService $service */
        $service = new FormationInstanceFraisService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}