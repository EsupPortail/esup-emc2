<?php

namespace Mailing\Service\MailType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class MailTypeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MailTypeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var MailTypeService $service */
        $service = new MailTypeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}