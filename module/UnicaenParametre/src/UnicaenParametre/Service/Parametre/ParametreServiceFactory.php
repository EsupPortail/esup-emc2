<?php

namespace UnicaenParametre\Service\Parametre;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ParametreServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return ParametreService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ParametreService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}