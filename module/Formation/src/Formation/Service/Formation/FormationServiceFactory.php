<?php

namespace Formation\Service\Formation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationService
     */
    public function __invoke(ContainerInterface $container) : FormationService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FormationService $service */
        $service = new FormationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
