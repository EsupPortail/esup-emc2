<?php

namespace Autoform\Service\Formulaire;

use Autoform\Service\Categorie\CategorieService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormulaireServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var CategorieService $categorieService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categorieService = $container->get(CategorieService::class);

        /** @var FormulaireService $service */
        $service = new FormulaireService();
        $service->setEntityManager($entityManager);
        $service->setCategorieService($categorieService);
        return $service;
    }
}