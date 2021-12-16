<?php

namespace UnicaenGlossaire\Service\Definition;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class DefinitionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DefinitionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new DefinitionService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}