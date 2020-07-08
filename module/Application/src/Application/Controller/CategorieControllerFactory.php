<?php

namespace Application\Controller;

use Application\Form\Categorie\CategorieForm;
use Application\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;

class CategorieControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var CategorieForm $categorieForm
         */
        $categorieService = $container->get(CategorieService::class);
        $categorieForm = $container->get('FormElementManager')->get(CategorieForm::class);

        /** @var CategorieController $controller */
        $controller = new CategorieController();
        $controller->setCategorieService($categorieService);
        $controller->setCategorieForm($categorieForm);
        return $controller;
    }
}