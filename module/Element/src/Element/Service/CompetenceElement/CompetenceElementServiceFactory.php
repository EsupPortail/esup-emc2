<?php

namespace Element\Service\CompetenceElement;

use Doctrine\ORM\EntityManager;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceElementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceElementService
    {
        /**
         * @var EntityManager $entityManager
         * @var NiveauMaitriseService $niveauService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $niveauService = $container->get(NiveauMaitriseService::class);

        $service = new CompetenceElementService();
        $service->setObjectManager($entityManager);
        $service->setNiveauMaitriseService($niveauService);
        return $service;
    }
}