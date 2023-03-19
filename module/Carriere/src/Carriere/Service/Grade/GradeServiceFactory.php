<?php

namespace Carriere\Service\Grade;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GradeServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return GradeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : GradeService
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
