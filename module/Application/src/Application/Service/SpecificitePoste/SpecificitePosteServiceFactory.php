<?php

namespace Application\Service\SpecificitePoste;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class SpecificitePosteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SpecificitePosteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var SpecificitePosteService $service */
        $service = new SpecificitePosteService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}