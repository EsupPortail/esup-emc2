<?php

namespace Metier\Service\FamilleProfessionnelle;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FamilleProfessionnelleServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FamilleProfessionnelleService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FamilleProfessionnelleService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new FamilleProfessionnelleService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}