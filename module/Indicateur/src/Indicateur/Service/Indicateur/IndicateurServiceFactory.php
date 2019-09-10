<?php

namespace Indicateur\Service\Indicateur;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class IndicateurServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var IndicateurService $service */
        $service = new IndicateurService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}