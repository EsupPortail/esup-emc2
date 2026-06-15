<?php

namespace FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut;

use Doctrine\ORM\EntityManager;
use Element\Service\CompetenceElement\CompetenceElementService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FicheMetierConfigurationCompetenceParDefautServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierConfigurationCompetenceParDefautService
    {
        /**
         * @var EntityManager $entityManager
         * @var CompetenceElementService $competenceElementService
         * @var FicheMetierService $ficheMetierService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $competenceElementService = $container->get(CompetenceElementService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        $service = new FicheMetierConfigurationCompetenceParDefautService();
        $service->setCompetenceElementService($competenceElementService);
        $service->setFicheMetierService($ficheMetierService);
        $service->setObjectManager($entityManager);
        return $service;
    }
}
