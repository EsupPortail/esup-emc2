<?php

namespace UnicaenNote\Service\Type;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class TypeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return TypeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        $service = new TypeService();
        $service->setEntityManager($entitymanager);
        return $service;
    }

}