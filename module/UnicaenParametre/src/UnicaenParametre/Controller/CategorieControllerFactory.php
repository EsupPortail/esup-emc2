<?php

namespace UnicaenParametre\Controller;

use Interop\Container\ContainerInterface;
use UnicaenParametre\Form\Categorie\CategorieForm;
use UnicaenParametre\Service\Categorie\CategorieService;

class CategorieControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         */
        $categorieService = $container->get(CategorieService::class);

        /**
         * @var CategorieForm $categorieForm
         */
        $categorieForm = $container->get('FormElementManager')->get(CategorieForm::class);

        $controller = new CategorieController();
        $controller->setCategorieService($categorieService);
        $controller->setCategorieForm($categorieForm);
        return $controller;
    }
}