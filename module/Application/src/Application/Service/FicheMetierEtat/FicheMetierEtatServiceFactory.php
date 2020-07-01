<?php

namespace Application\Service\FicheMetierEtat;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FicheMetierEtatServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierEtatService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FicheMetierEtatService $service */
        $service = new FicheMetierEtatService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}