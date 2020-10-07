<?php

namespace UnicaenDocument\Service\Exporter;

use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Contenu\ContenuService;

class ExporterServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ContenuService $contenuService
         */
        $contenuService = $container->get(ContenuService::class);

        /**
         * @var ExporterService $service
         */
        $service = new ExporterService();
        $service->setContenuService($contenuService);

        return $service;
    }
}