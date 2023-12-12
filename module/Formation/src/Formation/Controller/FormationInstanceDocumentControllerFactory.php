<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\Macro\MacroService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\InscriptionExterne\InscriptionExterneService;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenRenderer\Service\Rendu\RenduService;

class FormationInstanceDocumentControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceDocumentController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceDocumentController
    {
        /**
         * @var AgentService $agentService
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var InscriptionExterneService $inscriptionExterneService
         * @var MacroService $macroService
         * @var RenduService $renduService
         * @var SeanceService $seanceService
         * @var UrlService $urlService
         */
        $agentService = $container->get(AgentService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $inscriptionExterneService = $container->get(InscriptionExterneService::class);
        $macroService = $container->get(MacroService::class);
        $renduService = $container->get(RenduService::class);
        $seanceService = $container->get(SeanceService::class);
        $urlService = $container->get(UrlService::class);

        /* @var PhpRenderer $renderer */
        $renderer = $container->get('ViewRenderer');

        /** @var FormationInstanceDocumentController $controller */
        $controller = new FormationInstanceDocumentController();
        $controller->setAgentService($agentService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setInscriptionExterneService($inscriptionExterneService);
        $controller->setMacroService($macroService);
        $controller->setRenduService($renduService);
        $controller->setSeanceService($seanceService);
        $controller->setUrlService($urlService);
        $controller->setRenderer($renderer);

        return $controller;
    }
}