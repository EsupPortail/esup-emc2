<?php

namespace Application\Service\Perimetre;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class  PerimetreServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PerimetreService
    {
        /**
         * @var StructureService $structureService
         */
        $structureService = $container->get(StructureService::class);

        $service = new PerimetreService();
        $service->setStructureService($structureService);
        return $service;
    }
}