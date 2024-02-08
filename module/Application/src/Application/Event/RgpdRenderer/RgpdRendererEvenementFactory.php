<?php

namespace Application\Event\RgpdRenderer;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenRenderer\Service\Rendu\RenduService;

class RgpdRendererEvenementFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RgpdRendererEvenement
    {
        /**
         * @var RenduService $renduService
         * @var TypeService $typeService
         */
        $renduService = $container->get(RenduService::class);
        $typeService = $container->get(TypeService::class);

        $service = new RgpdRendererEvenement();

        $service->setRenduService($renduService);
        $service->setTypeService($typeService);
        return $service;
    }
}