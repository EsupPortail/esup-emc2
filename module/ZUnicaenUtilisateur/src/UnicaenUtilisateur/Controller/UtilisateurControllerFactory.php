<?php

namespace UnicaenUtilisateur\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenUtilisateur\Form\User\UserForm;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class UtilisateurControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        $userSearchFunctions = $container->get('Config')['unicaen-utilisateur']['recherche-individu'];
        $rechercheServices = [];
        foreach ($userSearchFunctions as $key => $service) {
            $rechercheServices[$key] = $container->get($service);
        }

        /**
         * @var RoleService $roleService
         * @var UserService $userService
         * @var MailingService $mailService
         * @var EntityManager $entityManager
         */
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);
        $mailService = $container->get(MailingService::class);

        /**
         * @var UserForm $userForm
         */
        $userForm = $container->get('FormElementManager')->get(UserForm::class);

        /** @var UtilisateurController $controller */
        $controller = new UtilisateurController();
        $controller->setRoleService($roleService);
        $controller->setUserService($userService);
        $controller->setMailingService($mailService);
        $controller->setUserForm($userForm);

        $controller->recherche = $rechercheServices;
        
        return $controller;
    }
}