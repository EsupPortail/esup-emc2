<?php

namespace Application\Form\AjouterFicheMetier;

use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AjouterFicheMetierHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AjouterFicheMetierHydrator
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);

        $hydrator = new AjouterFicheMetierHydrator();
        $hydrator->setFicheMetierService($ficheMetierService);
        return $hydrator;
    }
}