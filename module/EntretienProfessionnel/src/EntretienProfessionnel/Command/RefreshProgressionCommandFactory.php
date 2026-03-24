<?php

namespace EntretienProfessionnel\Command;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\CampagneProgressionStructure\CampagneProgressionStructureService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class RefreshProgressionCommandFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RefreshProgressionCommand
    {
        /**
         * @var CampagneService $campagneService
         * @var CampagneProgressionStructureService $campagneProgressionStructureService
         * @var StructureService $structureService
         */
        $campagneService = $container->get(CampagneService::class);
        $campagneProgressionStructureService = $container->get(CampagneProgressionStructureService::class);
        $structureService = $container->get(StructureService::class);

        $command = new RefreshProgressionCommand();
        $command->setCampagneService($campagneService);
        $command->setCampagneProgressionStructureService($campagneProgressionStructureService);
        $command->setStructureService($structureService);
        return $command;
    }
}