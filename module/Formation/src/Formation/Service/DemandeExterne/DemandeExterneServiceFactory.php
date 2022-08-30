<?php

namespace Formation\Service\DemandeExterne;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class DemandeExterneServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DemandeExterneService
    {
        /**
         * @var EntityManager $entityManager
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        $service = new DemandeExterneService();
        $service->setEntityManager($entityManager);
        $service->setValidationInstanceService($validationInstanceService);
        $service->setValidationTypeService($validationTypeService);
        return $service;
    }
}