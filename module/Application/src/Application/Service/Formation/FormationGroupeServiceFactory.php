<?php

namespace Application\Service\Formation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;
use Zend\View\Renderer\PhpRenderer;

class FormationGroupeServiceFactory {

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

        /** @var FormationGroupeService $service */
        $service = new FormationGroupeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setRenderer($renderer);
        return $service;
    }
}
