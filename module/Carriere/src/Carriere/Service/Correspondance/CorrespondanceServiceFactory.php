<?php

namespace Carriere\Service\Correspondance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CorrespondanceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var CorrespondanceService $service */
        $service = new CorrespondanceService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
