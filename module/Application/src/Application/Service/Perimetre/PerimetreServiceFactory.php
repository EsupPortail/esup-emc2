<?php

namespace Application\Service\Perimetre;

use Psr\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;

class  PerimetreServiceFactory {

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