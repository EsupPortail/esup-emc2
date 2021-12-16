<?php

namespace UnicaenEtat\Service\EtatType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class EtatTypeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new EtatTypeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}