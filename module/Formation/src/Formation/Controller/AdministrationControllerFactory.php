<?php

namespace Formation\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Parametre\ParametreService;

class AdministrationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AdministrationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AdministrationController
    {
        /**
         * @var CategorieService $categorieService
         * @var ParametreService $parametreService
         */
        $categorieService = $container->get(CategorieService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new AdministrationController();
        $controller->setCategorieService($categorieService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}