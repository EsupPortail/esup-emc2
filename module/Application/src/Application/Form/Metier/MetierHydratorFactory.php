<?php

namespace Application\Form\Metier;

use Application\Service\Categorie\CategorieService;
use Application\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;

class MetierHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var DomaineService $domaineService
         */
        $categorieService = $container->get(CategorieService::class);
        $domaineService = $container->get(DomaineService::class);

        $hydrator = new MetierHydrator();
        $hydrator->setCategorieService($categorieService);
        $hydrator->setDomaineService($domaineService);

        return $hydrator;
    }
}