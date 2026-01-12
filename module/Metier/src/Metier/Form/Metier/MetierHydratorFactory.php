<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
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
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         */
        $categorieService = $container->get(CategorieService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $hydrator = new MetierHydrator();
        $hydrator->setCategorieService($categorieService);
        $hydrator->setFamilleProfessionnelleService($familleProfessionnelleService);

        return $hydrator;
    }
}