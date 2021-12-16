<?php

namespace Metier\Service\FamilleProfessionnelle;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FamilleProfessionnelleServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FamilleProfessionnelleService
     */
    public function __invoke(ContainerInterface $container) : FamilleProfessionnelleService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FamilleProfessionnelleService $service */
        $service = new FamilleProfessionnelleService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}