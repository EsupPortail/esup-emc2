<?php

namespace UnicaenParametre\Form\Categorie;

use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Categorie\CategorieService;

class CategorieFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var CategorieHydrator $hydrator
         */
        $categorieService = $container->get(CategorieService::class);
        $hydrator = $container->get('HydratorManager')->get(CategorieHydrator::class);

        $form = new CategorieForm();
        $form->setCategorieService($categorieService);
        $form->setHydrator($hydrator);
        return $form;
    }
}