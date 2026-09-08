<?php

namespace Carriere\Form\SelectionnerCategories;

use Carriere\Service\Categorie\CategorieService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerCategoriesHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerCategoriesHydrator
    {
        /** @var CategorieService $categorieService */
        $categorieService = $container->get(CategorieService::class);

        $hydrator = new SelectionnerCategoriesHydrator();
        $hydrator->setCategorieService($categorieService);
        return $hydrator;
    }

}
