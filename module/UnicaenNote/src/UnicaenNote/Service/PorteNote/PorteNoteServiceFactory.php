<?php

namespace UnicaenNote\Service\PorteNote;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class PorteNoteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return PorteNoteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         * @var UserService $userService
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new PorteNoteService();
        $service->setEntityManager($entitymanager);
        $service->setUserService($userService);
        return $service;
    }

}