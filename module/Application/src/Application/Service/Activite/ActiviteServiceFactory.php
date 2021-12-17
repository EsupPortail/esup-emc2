<?php

namespace Application\Service\Activite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ActiviteServiceFactory {
    /**
     * @param ContainerInterface $container
     * @return ActiviteService
     */
    public function __invoke(ContainerInterface $container) : ActiviteService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ActiviteService $service */
        $service = new ActiviteService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}