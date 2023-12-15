<?php

namespace Formation\Service\InscriptionFrais;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionFraisServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return InscriptionFraisService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionFraisService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var InscriptionFraisService $service */
        $service = new InscriptionFraisService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}