<?php

namespace Application\Service\Validation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ValidationTypeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ValidationTypeService $service */
        $service = new ValidationTypeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}