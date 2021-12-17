<?php

namespace Application\Service\Expertise;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ExpertiseServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ExpertiseService
     */
    public function __invoke(ContainerInterface $container) : ExpertiseService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ExpertiseService $service */
        $service = new ExpertiseService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
