<?php

namespace UnicaenParametre\Controller;

use Interop\Container\ContainerInterface;
use UnicaenParametre\Form\Categorie\CategorieForm;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Parametre\ParametreService;

class CategorieControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var ParametreService $parametreService
         */
        $categorieService = $container->get(CategorieService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var CategorieForm $categorieForm
         */
        $categorieForm = $container->get('FormElementManager')->get(CategorieForm::class);

        $controller = new CategorieController();
        $controller->setCategorieService($categorieService);
        $controller->setParametreService($parametreService);
        $controller->setCategorieForm($categorieForm);
        return $controller;
    }
}