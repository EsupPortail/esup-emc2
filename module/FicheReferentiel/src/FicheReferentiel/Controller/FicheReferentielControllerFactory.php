<?php

namespace FicheReferentiel\Controller;

use FicheReferentiel\Service\FicheReferentiel\FicheReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FicheReferentielControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheReferentielController
    {
        /**
         * @var FicheReferentielService $ficheReferentielService
         */
        $ficheReferentielService = $container->get(FicheReferentielService::class);

        $controller = new FicheReferentielController();
        $controller->setFicheReferentielService($ficheReferentielService);
        return $controller;
    }
}