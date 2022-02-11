<?php

namespace Application\Service\Complement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ComplementServiceFactory {

    public function __invoke(ContainerInterface $container) : ComplementService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ComplementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}