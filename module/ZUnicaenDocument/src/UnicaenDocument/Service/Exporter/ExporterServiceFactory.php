<?php

namespace UnicaenDocument\Service\Exporter;

use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Contenu\ContenuService;
use Zend\View\Renderer\PhpRenderer;

class ExporterServiceFactory {

    public function __invoke(ContainerInterface $container) : ExporterService
    {
        /**
         * @var ContenuService $contenuService
         * @var $renderer PhpRenderer
         */
        $contenuService = $container->get(ContenuService::class);
        $renderer =  $container->get('ViewRenderer');

        /**
         * @var ExporterService $service
         */
        $service = new ExporterService($renderer);
        $service->setContenuService($contenuService);

        return $service;
    }
}