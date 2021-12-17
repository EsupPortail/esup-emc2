<?php

namespace Application\Service\ActiviteDescription;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ActiviteDescriptionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ActiviteDescriptionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ActiviteDescriptionService $service */
        $service = new ActiviteDescriptionService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}