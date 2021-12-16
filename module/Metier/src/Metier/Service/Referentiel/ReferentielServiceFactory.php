<?php

namespace Metier\Service\Referentiel;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ReferentielServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielService
     */
    public function __invoke(ContainerInterface $container) : ReferentielService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ReferentielService $service */
        $service = new ReferentielService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}