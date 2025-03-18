<?php

namespace Metier\Service\Referentiel;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferentielServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferentielService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ReferentielService();
        $service->setObjectManager($entityManager);

        return $service;
    }
}