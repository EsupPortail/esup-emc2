<?php


namespace EntretienProfessionnel\Form\Campagne;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;

class CampagneHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneHydrator
     */
    public function __invoke(ContainerInterface $container) : CampagneHydrator
    {
        /**
         * @var CampagneService $campagneService
         */
        $campagneService = $container->get(CampagneService::class);

        $hydrator = new CampagneHydrator();
        $hydrator->setCampagneService($campagneService);
        return $hydrator;
    }
}