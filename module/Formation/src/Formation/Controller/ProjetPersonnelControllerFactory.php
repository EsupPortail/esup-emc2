<?php

namespace Formation\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class ProjetPersonnelControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ProjetPersonnelController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ProjetPersonnelController
    {
        /**
         * @var ParametreService $parametreService
         */
        $parametreService = $container->get(ParametreService::class);

        $controller = new ProjetPersonnelController();
        $controller->setParametreService($parametreService);

        return $controller;
    }
}