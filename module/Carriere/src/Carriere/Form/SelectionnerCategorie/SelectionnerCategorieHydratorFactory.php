<?php

namespace Carriere\Form\SelectionnerCategorie;

use Carriere\Service\Categorie\CategorieService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerCategorieHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerCategorieHydrator
    {
        /** @var CategorieService $categorieService */
        $categorieService = $container->get(CategorieService::class);

        $hydrator = new SelectionnerCategorieHydrator();
        $hydrator->setCategorieService($categorieService);
        return $hydrator;
    }

}
