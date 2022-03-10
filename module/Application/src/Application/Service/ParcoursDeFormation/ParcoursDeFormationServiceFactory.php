<?php

namespace Application\Service\ParcoursDeFormation;

use Carriere\Service\Categorie\CategorieService;
use Metier\Service\Metier\MetierService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;

class ParcoursDeFormationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationService
     */
    public function __invoke(ContainerInterface $container) : ParcoursDeFormationService
    {
        /**
         * @var EntityManager $entityManager
         * @var CategorieService $categorieService
         * @var DomaineService $domaineService
         * @var MetierService $metierService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categorieService = $container->get(CategorieService::class);
        $domaineService = $container->get(DomaineService::class);
        $metierService = $container->get(MetierService::class);

        /** @var ParcoursDeFormationService $service */
        $service = new ParcoursDeFormationService();
        $service->setEntityManager($entityManager);
        $service->setCategorieService($categorieService);
        $service->setDomaineService($domaineService);
        $service->setMetierService($metierService);
        return $service;
    }
}