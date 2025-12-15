<?php

namespace Carriere\Form\Niveau;

use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauHydrator
    {
        /** @var CategorieService $categorieService */
        $categorieService = $container->get(CategorieService::class);

        $hydrator = new NiveauHydrator();
        $hydrator->setCategorieService($categorieService);
        return $hydrator;
    }
}