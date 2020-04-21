<?php

namespace Application\Service\Synchro;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class SynchroServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SynchroService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var SynchroService $service */
        $service = new SynchroService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}