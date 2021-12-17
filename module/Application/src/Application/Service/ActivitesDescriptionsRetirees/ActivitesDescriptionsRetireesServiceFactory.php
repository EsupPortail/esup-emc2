<?php

namespace Application\Service\ActivitesDescriptionsRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ActivitesDescriptionsRetireesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ActivitesDescriptionsRetireesService
     */
    public function __invoke(ContainerInterface $container) : ActivitesDescriptionsRetireesService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ActivitesDescriptionsRetireesService $service */
        $service = new ActivitesDescriptionsRetireesService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}