<?php

namespace Formation\Service\InscriptionExterne;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class InscriptionExterneServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionExterneService
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionExterneService
    {
        /**
         * @var EntityManager $entityManger
         */
        $entityManger = $container->get('doctrine.entitymanager.orm_default');

        $service = new InscriptionExterneService();
        $service->setObjectManager($entityManger);
        return $service;
    }
}