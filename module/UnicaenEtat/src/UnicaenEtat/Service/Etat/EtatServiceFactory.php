<?php

namespace UnicaenEtat\Service\Etat;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;
use Zend\View\Renderer\PhpRenderer;

class EtatServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatService
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

        $service = new EtatService();
        $service->setRenderer($renderer);
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}