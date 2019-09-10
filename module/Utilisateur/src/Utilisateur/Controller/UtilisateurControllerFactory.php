<?php

namespace Utilisateur\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenLdap\Service\People;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;

class UtilisateurControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var RoleService $roleService
         * @var People $ldapService
         * @var UserService $userService
         * @var MailingService $mailService
         * @var EntityManager $entityManager
         */
        $roleService = $container->get(RoleService::class);
        $ldapService = $container->get('LdapServicePeople');
        $userService = $container->get(UserService::class);
        $mailService = $container->get(MailingService::class);

        /** @var UtilisateurController $controller */
        $controller = new UtilisateurController();
        $controller->setRoleService($roleService);
        $controller->setUserService($userService);
        $controller->setLdapPeopleService($ldapService);
        $controller->setMailingService($mailService);

        return $controller;
    }
}