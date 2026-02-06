<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationRecopie;

use Doctrine\ORM\EntityManager;
use EntretienProfessionnel\Entity\Db\CampagneConfigurationRecopie;
use Psr\Container\ContainerInterface;

class CampagneConfigurationRecopieServiceFactory
{

    public function __invoke(ContainerInterface $container): CampagneConfigurationRecopieService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CampagneConfigurationRecopieService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}