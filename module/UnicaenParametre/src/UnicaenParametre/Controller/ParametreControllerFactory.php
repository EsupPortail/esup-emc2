<?php

namespace UnicaenParametre\Controller;

use Interop\Container\ContainerInterface;
use UnicaenParametre\Form\Parametre\ParametreForm;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Parametre\ParametreService;

class ParametreControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ParametreController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var ParametreService $parametreService
         */
        $categorieService = $container->get(CategorieService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var ParametreForm $parametreForm
         */
        $parametreForm = $container->get('FormElementManager')->get(ParametreForm::class);

        $controller = new ParametreController();
        $controller->setCategorieService($categorieService);
        $controller->setParametreService($parametreService);
        $controller->setParametreForm($parametreForm);
        return $controller;
    }
}