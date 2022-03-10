<?php

namespace Carriere\Service\Categorie;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CategorieServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieService
     */
    public function __invoke(ContainerInterface $container) : CategorieService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var CategorieService $service */
        $service = new CategorieService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}