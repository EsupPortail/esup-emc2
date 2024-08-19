<?php

namespace Formation\Form\Seance;

use Formation\Service\Lieu\LieuService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SeanceHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SeanceHydrator
    {
        /**
         * @var LieuService $lieuService
         */
        $lieuService = $container->get(LieuService::class);

        $hydrator = new SeanceHydrator();
        $hydrator->setLieuService($lieuService);
        return $hydrator;
    }
}