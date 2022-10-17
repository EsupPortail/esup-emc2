<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Seance\SeanceService;
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
    public function __invoke(ContainerInterface $container) : PresenceController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var PresenceService $presenceService
         * @var SeanceService $seanceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $presenceService = $container->get(PresenceService::class);
        $seanceService = $container->get(SeanceService::class);

        $controller = new PresenceController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setPresenceService($presenceService);
        $controller->setSeanceService($seanceService);
        return $controller;
    }
}