<?php

namespace UnicaenUtilisateur\Service\User;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleService;
use Zend\Authentication\AuthenticationService;


class UserServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return UserService
     */
    public function __invoke(ContainerInterface $container) {
        
        /**
         * @var EntityManager $entityManager
         * @var RoleService $roleService
         * @var UserContext $userContext
         * @var AuthenticationService $authenticationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $roleService = $container->get(RoleService::class);
        $userContext = $container->get(UserContext::class);
        $allConfig = $container->get('Config');
        $authenticationService = $container->get('Zend\Authentication\AuthenticationService');

        
        /** @var UserService $service */
        $service = new UserService();
        $service->setEntityManager($entityManager);
        $service->setRoleService($roleService);
        $service->setServiceUserContext($userContext);
        $service->setUserEntityClass($allConfig['zfcuser']['user_entity_class'] ?? User::class);
        $service->setAuthenticationService($authenticationService);

        return $service;
    }
}