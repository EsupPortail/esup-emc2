<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Exporter\ExporterService;
use UnicaenRenderer\Service\Rendu\RenduService;
use Zend\View\Renderer\PhpRenderer;

class FormationInstanceDocumentControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         * @var RenduService $renduService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
        $renduService = $container->get(RenduService::class);

        /* @var PhpRenderer $renderer */
        $renderer = $container->get('ViewRenderer');

        /** @var FormationInstanceDocumentController $controller */
        $controller = new FormationInstanceDocumentController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setRenduService($renduService);
        $controller->setRenderer($renderer);

        return $controller;
    }
}