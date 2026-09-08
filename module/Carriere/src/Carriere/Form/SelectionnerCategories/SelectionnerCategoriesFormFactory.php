<?php

namespace Carriere\Form\SelectionnerCategories;

use Carriere\Service\Categorie\CategorieService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerCategoriesFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerCategoriesForm
    {
        /**
         * @var CategorieService $categorieService
         * @var SelectionnerCategoriesHydrator $hydrator
         */
        $categorieService = $container->get(CategorieService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionnerCategoriesHydrator::class);

        $form = new SelectionnerCategoriesForm();
        $form->setCategorieService($categorieService);
        $form->setHydrator($hydrator);
        return $form;

    }
}
