<?php

namespace Carriere\Form\SelectionnerCategorie;

use Carriere\Service\Categorie\CategorieService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerCategorieFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerCategorieForm
    {
        /**
         * @var CategorieService $categorieService
         * @var SelectionnerCategorieHydrator $hydrator
         */
        $categorieService = $container->get(CategorieService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionnerCategorieHydrator::class);

        $form = new SelectionnerCategorieForm();
        $form->setCategorieService($categorieService);
        $form->setHydrator($hydrator);
        return $form;

    }
}
