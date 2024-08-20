<?php

namespace Carriere\Service\Categorie;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategorieServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CategorieService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CategorieService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CategorieService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}