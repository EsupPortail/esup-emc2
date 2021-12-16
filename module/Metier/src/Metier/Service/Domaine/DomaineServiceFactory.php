<?php

namespace Metier\Service\Domaine;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class DomaineServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineService
     */
    public function __invoke(ContainerInterface $container) : DomaineService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var DomaineService $service */
        $service = new DomaineService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}