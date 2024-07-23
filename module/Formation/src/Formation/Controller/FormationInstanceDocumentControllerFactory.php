<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\Macro\MacroService;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Session\SessionService;
use Formation\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
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
         * @var SessionService $sessionService
         * @var InscriptionService $inscriptionService
         * @var MacroService $macroService
         * @var RenduService $renduService
         * @var SeanceService $seanceService
         * @var UrlService $urlService
         */
        $agentService = $container->get(AgentService::class);
        $sessionService = $container->get(SessionService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $macroService = $container->get(MacroService::class);
        $renduService = $container->get(RenduService::class);
        $seanceService = $container->get(SeanceService::class);
        $urlService = $container->get(UrlService::class);


        /* @var PhpRenderer $renderer */
        $renderer = $container->get('ViewRenderer');

        $controller = new FormationInstanceDocumentController();
        $controller->setAgentService($agentService);
        $controller->setSessionService($sessionService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setMacroService($macroService);
        $controller->setRenduService($renduService);
        $controller->setSeanceService($seanceService);
        $controller->setUrlService($urlService);
        $controller->setRenderer($renderer);

        /** @var ParametreService $parametreService */
        $parametreService = $container->get(ParametreService::class);
        $controller->addValeur('image', $parametreService->getValeurForParametre(FormationParametres::TYPE, FormationParametres::LOGO));
        $controller->addValeur('libelle', $parametreService->getValeurForParametre(FormationParametres::TYPE, FormationParametres::LIBELLE));
        $controller->addValeur('souslibelle', $parametreService->getValeurForParametre(FormationParametres::TYPE, FormationParametres::SOUSLIBELLE));

        return $controller;
    }
}