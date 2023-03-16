<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MetierHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MetierHydrator
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