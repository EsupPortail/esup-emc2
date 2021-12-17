<?php

namespace EntretienProfessionnel\Service\Sursis;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class SursisServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SursisService
     */
    public function __invoke(ContainerInterface $container) : SursisService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new SursisService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}