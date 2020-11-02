<?php

namespace Application\Service\Grade;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class GradeServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return GradeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var GradeService $service */
        $service = new GradeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
