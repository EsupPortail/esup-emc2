<?php

namespace Application\Controller;

use Application\Service\FichePoste\FichePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SpecificiteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return SpecificiteController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SpecificiteController
    {
        /**
         * @var FichePosteService $fichePosteService
         * @var SpecificitePosteService $specificitePosteService
         */
        $fichePosteService = $container->get(FichePosteService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);

        $controller = new SpecificiteController();
        $controller->setFichePosteService($fichePosteService);
        $controller->setSpecificitePosteService($specificitePosteService);
        return $controller;
    }

}