<?php

namespace FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut;

use Doctrine\ORM\EntityManager;
use Element\Service\ApplicationElement\ApplicationElementService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FicheMetierConfigurationApplicationParDefautServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierConfigurationApplicationParDefautService
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationElementService $applicationElementService
         * @var FicheMetierService $ficheMetierService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationElementService = $container->get(ApplicationElementService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        $service = new FicheMetierConfigurationApplicationParDefautService();
        $service->setObjectManager($entityManager);
        $service->setApplicationElementService($applicationElementService);
        $service->setFicheMetierService($ficheMetierService);
        return $service;
    }
}
