<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Exporter\ExporterService;
use Zend\View\Renderer\PhpRenderer;

class FormationInstanceDocumentControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         * @var ExporterService $exporterService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
        $exporterService = $container->get(ExporterService::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FormationInstanceDocumentController $controller */
        $controller = new FormationInstanceDocumentController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setExporterService($exporterService);
        $controller->setRenderer($renderer);

        return $controller;
    }
}