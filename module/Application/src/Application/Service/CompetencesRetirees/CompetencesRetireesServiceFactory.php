<?php

namespace Application\Service\CompetencesRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CompetencesRetireesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetencesRetireesService
     */
    public function __invoke(ContainerInterface $container) : CompetencesRetireesService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CompetencesRetireesService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}