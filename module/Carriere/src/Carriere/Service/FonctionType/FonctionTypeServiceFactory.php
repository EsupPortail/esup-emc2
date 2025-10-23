<?php

namespace Carriere\Service\FonctionType;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class FonctionTypeServiceFactory
{

    public function __invoke(ContainerInterface $container): FonctionTypeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get(EntityManager::class);

        $service = new FonctionTypeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}
