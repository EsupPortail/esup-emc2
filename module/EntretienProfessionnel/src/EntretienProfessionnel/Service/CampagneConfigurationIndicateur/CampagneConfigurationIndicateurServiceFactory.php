<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationIndicateur;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class CampagneConfigurationIndicateurServiceFactory
{

    public function __invoke(ContainerInterface $container): CampagneConfigurationIndicateurService
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CampagneConfigurationIndicateurService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}
