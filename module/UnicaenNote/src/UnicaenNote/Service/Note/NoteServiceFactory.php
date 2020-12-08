<?php

namespace UnicaenNote\Service\Note;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class NoteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NoteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         * @var UserService $userService
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new NoteService();
        $service->setEntityManager($entitymanager);
        $service->setUserService($userService);
        return $service;
    }

}