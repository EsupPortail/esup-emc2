<?php

namespace Application\Service\NiveauEnveloppe;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class NiveauEnveloppeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var NiveauEnveloppeService $service */
        $service = new NiveauEnveloppeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}