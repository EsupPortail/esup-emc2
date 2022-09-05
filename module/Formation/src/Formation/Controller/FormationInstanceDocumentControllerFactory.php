<?php

namespace Formation\Controller;

use Application\Service\Macro\MacroService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Formation\Service\Seance\SeanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenRenderer\Service\Rendu\RenduService;
use Laminas\View\Renderer\PhpRenderer;

class FormationInstanceDocumentControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceDocumentController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationInstanceDocumentController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var MacroService $macroService
         * @var RenduService $renduService
         * @var SeanceService $seanceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $macroService = $container->get(MacroService::class);
        $renduService = $container->get(RenduService::class);
        $seanceService = $container->get(SeanceService::class);

        /* @var PhpRenderer $renderer */
        $renderer = $container->get('ViewRenderer');

        /** @var FormationInstanceDocumentController $controller */
        $controller = new FormationInstanceDocumentController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setMacroService($macroService);
        $controller->setRenduService($renduService);
        $controller->setSeanceService($seanceService);
        $controller->setRenderer($renderer);

        return $controller;
    }
}