<?php

namespace Application\Service\Validation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ValidationValeurServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ValidationValeurService $service */
        $service = new ValidationValeurService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}