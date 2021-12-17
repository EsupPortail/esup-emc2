<?php

namespace Application\Service\Niveau;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class NiveauServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var NiveauService $service */
        $service = new NiveauService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}