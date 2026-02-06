<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationRecopie;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Champ\ChampService;

class CampagneConfigurationRecopieHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationRecopieHydrator
    {
        /**
         * @var ChampService $champService
         */
        $champService = $container->get(ChampService::class);

        $hydrator = new CampagneConfigurationRecopieHydrator();
        $hydrator->setChampService($champService);
        return $hydrator;
    }
}