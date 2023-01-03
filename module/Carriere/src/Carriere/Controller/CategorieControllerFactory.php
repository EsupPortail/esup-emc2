<?php

namespace Carriere\Controller;

use Carriere\Form\Categorie\CategorieForm;
use Carriere\Service\Categorie\CategorieService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategorieControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CategorieController
    {
        /**
         * @var CategorieService $categorieService
         * @var CategorieForm $categorieForm
         */
        $categorieService = $container->get(CategorieService::class);
        $categorieForm = $container->get('FormElementManager')->get(CategorieForm::class);

        $controller = new CategorieController();
        $controller->setCategorieService($categorieService);
        $controller->setCategorieForm($categorieForm);
        return $controller;
    }
}