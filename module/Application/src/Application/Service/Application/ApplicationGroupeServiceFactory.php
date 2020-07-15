<?php

namespace Application\Service\Application;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;
use Zend\View\Renderer\PhpRenderer;

class ApplicationGroupeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationGroupeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var ApplicationGroupeService $service */
        $service = new ApplicationGroupeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setRenderer($renderer);
        return $service;
    }
}
