<?php

namespace Carriere\Service\Correspondance;

use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CorrespondanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CorrespondanceService
    {
        /**
         * @var EntityManager $entityManager
         * @var CorrespondanceTypeService $correspondanceTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $correspondanceTypeService = $container->get(CorrespondanceTypeService::class);

        $service = new CorrespondanceService();
        $service->setObjectManager($entityManager);
        $service->setCorrespondanceTypeService($correspondanceTypeService);
        return $service;
    }
}
