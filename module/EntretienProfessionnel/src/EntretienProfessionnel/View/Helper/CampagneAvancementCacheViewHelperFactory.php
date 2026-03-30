<?php

namespace EntretienProfessionnel\View\Helper;

use EntretienProfessionnel\Service\CampagneProgressionStructure\CampagneProgressionStructureService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CampagneAvancementCacheViewHelperFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneAvancementCacheViewHelper
    {
        /**
         * @var CampagneProgressionStructureService $progressionService
         */
        $progressionService = $container->get(CampagneProgressionStructureService::class);

        $helper = new CampagneAvancementCacheViewHelper();
        $helper->setCampagneProgressionStructureService($progressionService);
        return $helper;
    }
}
