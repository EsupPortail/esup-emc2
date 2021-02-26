<?php

namespace UnicaenParametre\Service\Categorie;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CategorieServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CategorieService();
        $service->setEntityManager($entityManager);
        return $service;
    }

}