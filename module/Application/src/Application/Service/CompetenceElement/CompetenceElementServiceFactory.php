<?php

namespace Application\Service\CompetenceElement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CompetenceElementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CompetenceElementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}