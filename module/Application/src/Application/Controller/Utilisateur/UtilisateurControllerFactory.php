<?php

namespace Application\Controller\Utilisateur;

use Application\Service\Role\RoleService;
use Application\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Mailing\Service\Mailing\MailingService;
use UnicaenLdap\Service\People;
use Zend\Mvc\Controller\ControllerManager;

class UtilisateurControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var RoleService $roleService
         * @var People $ldapService
         * @var UserService $userService
         * @var MailingService $mailService
         * @var EntityManager $entityManager
         */
        $roleService = $controllerManager->getServiceLocator()->get(RoleService::class);
        $ldapService = $controllerManager->getServiceLocator()->get('LdapServicePeople');
        $userService = $controllerManager->getServiceLocator()->get(UserService::class);
        $mailService = $controllerManager->getServiceLocator()->get(MailingService::class);

        /** @var UtilisateurController $controller */
        $controller = new UtilisateurController();
        $controller->setRoleService($roleService);
        $controller->setUserService($userService);
        $controller->setLdapPeopleService($ldapService);
        $controller->setMailingService($mailService);


        return $controller;
    }
}