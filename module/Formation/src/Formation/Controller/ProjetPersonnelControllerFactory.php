<?php

namespace Formation\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;

class ProjetPersonnelControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return ProjetPersonnelController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ProjetPersonnelController
    {
        /**
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         */
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);

        $controller = new ProjetPersonnelController();
        $controller->setParametreService($parametreService);
        $controller->setRenduService($renduService);

        return $controller;
    }
}