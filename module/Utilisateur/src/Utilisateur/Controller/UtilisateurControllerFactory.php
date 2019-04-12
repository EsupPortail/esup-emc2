<?php

namespace Utilisateur\Controller;

use Doctrine\ORM\EntityManager;
use Mailing\Service\Mailing\MailingService;
use UnicaenLdap\Service\People;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class UtilisateurControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var RoleService $roleService
         * @var People $ldapService
         * @var UserService $userService
         * @var MailingService $mailService
         * @var EntityManager $entityManager
         */
        $roleService = $manager->getServiceLocator()->get(RoleService::class);
        $ldapService = $manager->getServiceLocator()->get('LdapServicePeople');
        $userService = $manager->getServiceLocator()->get(UserService::class);
        $mailService = $manager->getServiceLocator()->get(MailingService::class);

        /** @var UtilisateurController $controller */
        $controller = new UtilisateurController();
        $controller->setRoleService($roleService);
        $controller->setUserService($userService);
        $controller->setLdapPeopleService($ldapService);
        $controller->setMailingService($mailService);

        return $controller;
    }
}