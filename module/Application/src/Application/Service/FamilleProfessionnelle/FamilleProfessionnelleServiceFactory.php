<?php

namespace Application\Service\FamilleProfessionnelle;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FamilleProfessionnelleServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FamilleProfessionnelleService $service */
        $service = new FamilleProfessionnelleService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}