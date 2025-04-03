<?php

namespace EntretienProfessionnel\Service\Ics;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class IcsServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): IcsService
    {
        /**
         * @var ParametreService $parametreService
         */
        $parametreService = $container->get(ParametreService::class);

        $service = new IcsService();
        $service->setParametreService($parametreService);
        return $service;
    }
}