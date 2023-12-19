<?php

namespace Formation\Service\PlanDeFormation;

use Doctrine\ORM\EntityManager;
use Formation\Service\Formation\FormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PlanDeFormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return PlanDeFormationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PlanDeFormationService
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationService $formationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formationService = $container->get(FormationService::class);

        $service = new PlanDeFormationService();
        $service->setObjectManager($entityManager);
        $service->setFormationService($formationService);
        return $service;
    }
}