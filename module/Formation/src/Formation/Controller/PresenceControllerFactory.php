<?php

namespace Formation\Controller;

use Formation\Service\Inscription\InscriptionService;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PresenceControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return PresenceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PresenceController
    {
        /**
         * @var SessionService $sessionService
         * @var InscriptionService $inscriptionService
         * @var PresenceService $presenceService
         * @var SeanceService $seanceService
         */
        $sessionService = $container->get(SessionService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $presenceService = $container->get(PresenceService::class);
        $seanceService = $container->get(SeanceService::class);

        $controller = new PresenceController();
        $controller->setSessionService($sessionService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setPresenceService($presenceService);
        $controller->setSeanceService($seanceService);
        return $controller;
    }
}