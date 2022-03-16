<?php

namespace Carriere\Service\NiveauEnveloppe;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class NiveauEnveloppeServiceFactory {

    public function __invoke(ContainerInterface $container) : NiveauEnveloppeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var NiveauEnveloppeService $service */
        $service = new NiveauEnveloppeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}