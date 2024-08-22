<?php

namespace FicheReferentiel\Service\FicheReferentiel;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class FicheReferentielServiceFactory {

    public function __invoke(ContainerInterface $container): FicheReferentielService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new FicheReferentielService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}