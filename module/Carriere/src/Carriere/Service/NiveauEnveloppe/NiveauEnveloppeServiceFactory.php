<?php

namespace Carriere\Service\NiveauEnveloppe;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauEnveloppeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauEnveloppeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauEnveloppeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new NiveauEnveloppeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}