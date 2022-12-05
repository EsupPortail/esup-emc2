<?php

namespace Carriere\Controller;

use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CorrespondanceTypeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceTypeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CorrespondanceTypeController
    {
        /**
         * @var CorrespondanceService $correspondaceService
         * @var CorrespondanceTypeService $typeService
         */
        $correspondaceService = $container->get(CorrespondanceService::class);
        $typeService = $container->get(CorrespondanceTypeService::class);

        $controller = new CorrespondanceTypeController();
        $controller->setCorrespondanceService($correspondaceService);
        $controller->setCorrespondanceTypeService($typeService);
        return $controller;
    }
}